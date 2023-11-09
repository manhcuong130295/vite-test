<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\Relationships\ZaloChannelRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZaloChannels extends Model
{
    use HasFactory,
        ZaloChannelRelationship;

    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $appends = [
    ];
}
