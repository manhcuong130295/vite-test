<?php

namespace App\Repositories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionPlanRepository extends BaseRepository
{
    /**
     * CustomerRepository constructor.
     *
     * @param Customers $model
     */
    public function __construct(SubscriptionPlan $model)
    {
        $this->model = $model;
    }

    /**
     * List customer
     *
     * @param array $conditions
     */
    public function getList(array $columns = ['*'])
    {
        return $this->model->select($columns)->get();
    }

    /**
     * Get the subscription type for the given plan ID.
     *
     * @param string $planId
     * @return string|null
     */
    public function getSubscriptionType($planId)
    {
        $model = $this->model->find($planId);

        if (!$model) {
            return null;
        }

        return $model->type;
    }
}
