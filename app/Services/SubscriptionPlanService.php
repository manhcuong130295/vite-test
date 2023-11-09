<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\SubscriptionPlanRepository;

class SubscriptionPlanService
{
    /**
     * @var SubscriptionPlanRepository
     */
    protected $subscriptionPlanRepository;

    /**
     * CustomerService constructor.
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        SubscriptionPlanRepository $subscriptionPlanRepository
    ) {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }

    /**
     * Get list
     *
     * @Return list subscription
     *
     */
    public function getList()
    {
        return $this->subscriptionPlanRepository->getList();
    }

}
