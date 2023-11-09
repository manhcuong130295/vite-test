<?php

namespace App\Adapters\Stripes;

use App\Constants\SubscriptionStatus;
use App\Events\HasSubscriptionEvent;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\CustomerService;
use App\Models\Customer;
use App\Transformers\SuccessResource;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\InvalidRequestException;
use App\Models\User;
use App\Repositories\SubscriptionPlanRepository;
use Stripe\Customer as CustomerStripe;
use Stripe\PaymentMethod;


class StripeWebhookService extends StripeAbstract
{
    private $stripe;
     /**
     * @var CustomerService
     */
    protected $customerService;

    /**
     * @var SubscriptionPlanRepository
     */
    protected $subscriptionPlanRepository;

    /**
     * @param CustomerService $customerService
     * @param SubscriptionPlanRepository $subscriptionPlanRepository
     */
    public function __construct(
        CustomerService $customerService,
        SubscriptionPlanRepository $subscriptionPlanRepository
    ) {
        $this->stripe = $this->setApiKey();
        $this->customerService = $customerService;
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }

    public function webhook()
    {
        $payload = file_get_contents('php://input');
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

          // Handle the event
          switch ($event->type) {
            case 'invoice.payment_failed':
              Log::info("=== invoice.payment_failed ===");
              $subscription = $event->data->object;
              $this->handleInvoicePaymentFailed($subscription);
              break;
            case 'invoice.payment_succeeded':
              Log::info("=== invoice.payment_succeeded ===");
              $subscription = $event->data->object;
              $this->handleInvoicePaymentSucceed($subscription);
              break;
            // ... handle other event types
            default:
              echo 'Received unknown event type ' . $event->type;
          }

          return new SuccessResource();
    }

    private function handleInvoicePaymentSucceed($subscription)
    {
        try {
            $user_uuid = $subscription['subscription_details']['metadata']['user_uuid'];
            if (empty($user_uuid)) {
                Log::error("USER_UUID DON'T EXIT, SUB_ID:". $subscription['subscription']);
                http_response_code(422);
                exit();
            }
            $isExist = User::select("*")
            ->where("uuid", $user_uuid)
            ->exists();

            if (!$isExist) {
                Log::error("USER DON'T EXIT, UUID:". $user_uuid);
                http_response_code(422);
                exit();
            }

            $data = [
                'user_uuid' => $subscription['subscription_details']['metadata']['user_uuid'],
                'subscription_plan_id' => (int) $subscription['subscription_details']['metadata']['subscription_plan_id'],
                'customer_stripe_id' => $subscription['customer'],
                'subscription_id' => $subscription['subscription'],
                'name' => $subscription['customer_name'],
                'email' => $subscription['customer_email'],
                'start_date' => date('Y-m-d H:i:s', $subscription['created']),
                'due_date' => $subscription['due_date'] ? date('Y-m-d H:i:s', $subscription['due_date']) : date('Y-m-d H:i:s', strtotime('+1 month', $subscription['created'])),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $this->customerService->updateOrCreate([
                'user_uuid'   => $subscription['subscription_details']['metadata']['user_uuid'],
            ], $data);

            // $this->updateTrailEnd($subscription['subscription']);
            $this->logSlackNotification("Subscription {$this->subscriptionPlanRepository->getSubscriptionType($data['subscription_plan_id'])} succeed", SubscriptionStatus::SUCCESS,  $subscription['subscription_details']['metadata']['user_uuid']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return new SuccessResource();
    }

    private function handleInvoicePaymentFailed($subscription)
    {
        try {
            $user_uuid = $subscription['subscription_details']['metadata']['user_uuid'];
            if (empty($user_uuid)) {
                Log::error("USER_UUID DON'T EXIT, SUB_ID:". $subscription['subscription']);
                http_response_code(422);
                exit();
            }
            $this->handleCancelSubscription($subscription['subscription']);
            $data = [
                'subscription_plan_id' => 1,
            ];
            $this->customerService->updateOrCreate([
                'user_uuid'   => $subscription['subscription_details']['metadata']['user_uuid'],
            ], $data);
            $this->logSlackNotification('Subscription failed', SubscriptionStatus::FAIL, $user_uuid);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        http_response_code(200);
        exit();
    }

    private function updateTrailEnd($subscriptionId)
    {
        $nextPaymentDay = strtotime('+1 month', time());
        // $hour = 0;
        // $minute = 0;
        // $nextPaymentDayFull = mktime($hour, $minute, 0, date('m', $nextPaymentDay), 1, date('Y', $nextPaymentDay));

        $this->stripe->subscriptions->update(
            $subscriptionId,
            [
              'trial_end' => ($nextPaymentDay),
              'proration_behavior' => 'none'
            ]
        );
    }

    /**
     * Cancel subscription to stripe
     * @return bool
     */
    public function handleCancelSubscription($subscriptionId)
    {
        $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
        if ($subscription && $subscription['status'] == "active") {
            $subscriptionCancel = $this->stripe->subscriptions->cancel($subscriptionId);
            if ($subscriptionCancel['status'] == 'canceled') {
                $this->logSlackNotification('Cancel subscription');
                return true;
            }
        }

        return false;
    }

    /**
     * update subscription to stripe
     * @param Customer $customer
     * @param string $price_id
     *
     * @return bool
     */
    public function handleUpdateSubscription($customer, $price_id, $plan_id)
    {
        $customerStripe = $this->stripe->subscriptions->all(['customer' => $customer->customer_stripe_id]);
        $subscription = $this->stripe->subscriptions->retrieve($customer->subscription_id);
        if ($customerStripe && $subscription && $subscription['status'] == "active") {
            $subscriptionItemId = $customerStripe['data'][0]['items']['data'][0]['id'];
            $subscriptionId = $customer->subscription_id;
            $newSubscription = $this->stripe->subscriptions->update(
                $subscriptionId,
                [
                    'items' => [
                      [
                        'id' => $subscriptionItemId,
                        'price' => $price_id,
                      ],
                    ],
                  ]
              );
            if ($newSubscription->status === 'active') {
                $this->customerService->updateSubscription(Auth::user()->uuid, $plan_id);
                $this->logSlackNotification("Update from {$customer->subscriptionPlan->type} to {$this->subscriptionPlanRepository->getSubscriptionType($plan_id)} subscription successfully");

                return true;
            }
        }

        return false;
    }

    /**
     * get invoice by customer
     * @param string $customer
     */
    public function getInvoice($customerId)
    {
        try {
            $invoices = $this->stripe->invoices->all([
                'customer' => $customerId,
            ]);

            return $invoices;
        } catch (InvalidRequestException $e) {
            Log::debug($e->getMessage());
            return [];
        }
    }

    /**
     * get payment by customer
     * @param string $customer
     */
    public function getPayment($customerId)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);
            if (!$paymentMethods) {
                return [];
            }

            usort($paymentMethods->data, function ($a, $b) {
                return $b->created - $a->created;
            });

            $lastPaymentMethod = reset($paymentMethods->data);
            if($lastPaymentMethod) {
                return $lastPaymentMethod;
            }


            return [];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return [];
        }
    }

    /**
     * Log slack notification
     * @param string $message
     * @param string $status
     */
    private function logSlackNotification($message, $status = null, $user_uuid = null)
    {
        try {
            event(new HasSubscriptionEvent($message, $status, $user_uuid));
        } catch (\Throwable $th) {
            Log::info('===SLACK NOTIFICATION ERROR===' . $th->getMessage());
        }
    }
}
