<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\Relationships\LineChannelRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineChannels extends Model
{
    use HasFactory,
        LineChannelRelationship;

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
