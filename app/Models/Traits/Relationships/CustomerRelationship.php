<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CustomerRelationship
{
    // ====================================
    // HasMany relationships
    // ====================================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    // ====================================
    // HasMany relationships
    // ====================================
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }
}
