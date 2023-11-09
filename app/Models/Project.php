<?php

namespace App\Models;

use App\Models\Traits\Relationships\ProjectRelationship;
use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, UuidTrait, ProjectRelationship;

    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_uuid',
        'name',
        'content',
        'processing_status'
    ];
}
