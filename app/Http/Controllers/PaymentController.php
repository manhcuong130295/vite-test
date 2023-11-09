<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\SubscriptionPlanService;
use App\Services\CustomerService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * @var SubscriptionPlanService
     */
    private $subscriptionPlan;

    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(
        SubscriptionPlanService $subscriptionPlan,
        CustomerService $customerService,
        UserService $userService
    )
    {
        $this->subscriptionPlan = $subscriptionPlan;
        $this->customerService = $customerService;
        $this->userService = $userService;
    }

    public function showCheckoutPage()
    {
        $subscription = $this->subscriptionPlan->getList();
        $customer = $this->customerService->getByUser(Auth::user()->uuid);

        $user = $this->userService->detail(auth()->user()->id);

        $subscriptionPlan = $user->customer ? $user->customer->subscriptionPlan : null;

        return view('payment.list',[
            'subscription' => $subscription,
            'customer' => $customer,
            'subscriptionPlan' => $subscriptionPlan
        ]);
    }
}
