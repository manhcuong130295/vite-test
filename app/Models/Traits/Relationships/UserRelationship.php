<?php

namespace App\Models\Traits\Relationships;

use App\Models\Customer;
use App\Models\Project;
use App\Models\RequestStatistic;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationship
{
    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_uuid', 'uuid');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_uuid', 'uuid');
    }

    public function requestStatistics(): HasMany
    {
        return $this->hasMany(RequestStatistic::class, 'user_uuid', 'uuid');
    }
}
