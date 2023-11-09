<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\Relationships\CustomerRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory,
        CustomerRelationship;

    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
