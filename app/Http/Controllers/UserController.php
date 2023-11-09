<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Adapters\Stripes\StripeWebhookService;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @var UserService $userService
     */
    protected UserService $userService;

     /**
     * @var StripeWebhookService
     */
    private $stripeWebhookService;

    public function __construct(UserService $userService, StripeWebhookService $stripeWebhookService)
    {
        $this->userService = $userService;
        $this->stripeWebhookService = $stripeWebhookService;
    }

    /**
     * View list project.
     *
     * @return view
     */
    public function profile (): View
    {

        $user = $this->userService->detail(auth()->user()->id);
        $invoices = [];
        $paymentCustomer = [];
        if ($user->customer && $user->customer->subscription_id) {
            $invoices = $this->stripeWebhookService->getInvoice($user->customer->customer_stripe_id);
            $paymentCustomer = $this->stripeWebhookService->getPayment($user->customer->customer_stripe_id);
        }
        $stripe_pk = config('services.stripe.key');

        return view('user/profile', compact('user', 'invoices', 'stripe_pk', 'paymentCustomer'));
    }
}
