<?php

namespace App\Models;

use App\Models\Traits\Relationships\ProjectContentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContent extends Model
{
    use HasFactory, ProjectContentRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'name',
        'text',
        'type',
        'content_id'
    ];
}
