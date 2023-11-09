<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\CustomerRepository;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Token;
use Stripe\Customer;

class CustomerService
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * CustomerService constructor.
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Save customer
     * @return array $data
     */
    public function create($data)
    {
        return $this->customerRepository->store($data);
    }

    /**
     * updateOrCreate customer
     * @param string user_uuid
     * @param array $data
     *
     * @return array $data
     */
    public function  updateOrCreate($user_uuid, $data) {
        return $this->customerRepository->updateOrCreate($user_uuid, $data);
    }

    /**
     * Get by id
     * @param int id
     *
     */
    public function getById(int $id)
    {
        return $this->customerRepository->getById($id);
    }

    /**
     * Get by user_uuid
     * @param string user_uuid
     *
     */
    public function getByUser(string $user_uuid)
    {
        return $this->customerRepository->getByUser($user_uuid);
    }

    /**
     * Delete customer
     * @param int $id
     */
    public function deleteCustomer($id)
    {
        return $this->customerRepository->deleteCustomer($id);
    }

    /**
     * cancel subscription
     * @param string user_uuid
     */
    public function cancelSubscription($user_uuid)
    {
        return $this->customerRepository->cancelSubscription($user_uuid);
    }

    /**
     * update subscription
     * @param string user_uuid
     * @param int $priceId
     */
    public function updateSubscription($user_uuid, $priceId)
    {
        return $this->customerRepository->updateSubscription($user_uuid, $priceId);
    }

    /**
     * update card
     * @param request $request
     */
    public function updateCard($request) {
        $token = $request->input('stripeToken');

        if (!$request->input('user_id')) {
            return false;
        } else {
            $customer = $this->customerRepository->getByUser($request->input('user_id'));

            if ($customer && !empty($customer->customer_stripe_id)) {
                $customerStripeId = $customer->customer_stripe_id;
                try {
                    Stripe::setApiKey(config('services.stripe.secret_key'));
                    $stripeCustomer = Customer::retrieve($customerStripeId);
                    $stripeCustomer->source = $token;
                    $stripeCustomer->save();
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * delete card
     * @param request $request
     */
    public function deleteCard($request) {
        $cardId = $request->input('card_id');
        if (!$request->input('user_id')) {
            return false;
        } else {
            $customer = $this->customerRepository->getByUser($request->input('user_id'));

            if ($customer && !empty($customer->customer_stripe_id)) {
                $customerStripeId = $customer->customer_stripe_id;
                try {
                    $stripe = new StripeClient(config('services.stripe.secret_key'));
                    if (strpos($cardId, 'pm_') === 0) {
                        $stripe->paymentMethods->detach($cardId, []);
                    } elseif (strpos($cardId, 'card_') === 0) {
                        $stripe->customers->deleteSource($customerStripeId, $cardId, []);
                    } else {
                        return false;
                    }
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
