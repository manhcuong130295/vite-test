<?php

namespace App\Models;

use App\Models\Traits\Relationships\FacebookFanpageRelationship;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookFanpage extends Model
{
    use HasFactory, UuidTrait, FacebookFanpageRelationship;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'page_id',
        'project_id',
        'access_token',
        'status'
    ];
}
