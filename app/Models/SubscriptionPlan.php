<?php

namespace App\Models;

use App\Models\Traits\Relationships\SubscriptionPlanRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory, SubscriptionPlanRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'max_project',
        'max_character',
        'max_message',
        'price'
    ];
}
