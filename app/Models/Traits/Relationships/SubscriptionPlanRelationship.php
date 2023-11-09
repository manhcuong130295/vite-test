<?php

namespace App\Models\Traits\Relationships;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Customer;

trait SubscriptionPlanRelationship
{
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'subscription_plan_id', 'id');
    }
}
