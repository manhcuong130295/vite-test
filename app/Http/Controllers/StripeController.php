<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Checkout\Session;
use App\Constants\Price;
use Illuminate\Support\Facades\Auth;
use App\Adapters\Stripes\StripeWebhookService;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Services\CustomerService;

class StripeController extends Controller
{
    /**
     * @var StripeWebhookService
     */
    private $stripeWebhookService;

    /**
     * @var CustomerService
     */
    protected $customerService;

    public function __construct(
        StripeWebhookService $stripeWebhookService,
        CustomerService $customerService
    )
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
        $this->stripeWebhookService = $stripeWebhookService;
        $this->customerService = $customerService;
    }



    /**
     * call page checkout stripe
     * @param Request $request
     */
    public function checkout(Request $request)
    {
        $priceId = $request->get('price_id');
        if (!$priceId && $priceId !== 1) {
            return view('payment.list');
        }
        $customer = $this->customerService->getByUser(Auth::user()->uuid);

        if ($customer && $customer->subscription_id && $customer->subscription_plan_id != 1) {
            if ($priceId > $customer->subscription_plan_id) {
                return $this->upgrade($request);
            } elseif(($priceId < $customer->subscription_plan_id)) {
                return $this->downgrade($request);
            }
        }
        header('Content-Type: application/json');

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => env(Price::price[$priceId]['price_id']),
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
            'subscription_data' => [
                'metadata' => [
                    'user_uuid' => Auth::user()->uuid ?? Auth::user()->uuid,
                    'subscription_plan_id' => $priceId
                ],
            ],
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);

    }

    /**
     * upgrade subscription
     * @param Request $request
     */
    public function upgrade(Request $request)
    {
        $priceId = $request->get('price_id');
        $customer = $this->customerService->getByUser(Auth::user()->uuid);
        if ($customer && $customer->subscription_id) {
            //upgrade subscription
            if($this->stripeWebhookService->handleUpdateSubscription($customer, env(Price::price[$priceId]['price_id']), $request->get('price_id'))) {
                return redirect()->route('payment.list')->withSuccess('The registration has been changed successfully');
            }
            return redirect()->route('payment.list')->with(array('message' => 'Subscription cannot be upgrade, please try again later.'));
        }
    }

    /**
     * downgrade subscription
     * @param Request $request
     */
    public function downgrade(Request $request)
    {
        $priceId = $request->get('price_id');
        $customer = $this->customerService->getByUser(Auth::user()->uuid);
        if ($customer && $customer->subscription_id) {
            if ($priceId == 1) {
                if($this->stripeWebhookService->handleCancelSubscription($customer->subscription_id)) {
                    $this->customerService->cancelSubscription(Auth::user()->uuid);
                    return redirect()->route('payment.list')->withSuccess('Your subscription has been successfully cancelled.');
                }
                return redirect()->route('payment.list')->with(array('message' => 'Subscription cannot be canceled, please try again later.'));
            }
            //downgrade subscription
            if($this->stripeWebhookService->handleUpdateSubscription($customer, env(Price::price[$priceId]['price_id']), $request->get('price_id'))) {
                return redirect()->route('payment.list')->withSuccess('The registration has been changed successfully');
            }
            return redirect()->route('payment.list')->with(array('message' => 'Subscription cannot be downgrade, please try again later.'));
        }
    }

    //callback stripe success
    public function success(Request $request)
    {
        return redirect()->route('payment.list')->withSuccess('You have successfully registered.');

    }

    //callback stripe cancel
    public function cancel()
    {
        return redirect()->route('payment.list')->withErrors("You have unsubscribed.");
    }

    public function createCustomer()
    {
        #TODO
    }

    public function createSubscription()
    {
        #TODO
    }

    public function getNextPayment()
    {
        #TODO
    }
}
