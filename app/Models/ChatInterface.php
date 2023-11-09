<?php

namespace App\Models;

use App\Models\Traits\Relationships\ChatInterfaceRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatInterface extends Model
{
    use HasFactory, ChatInterfaceRelationship;

    public $timestamps = true;

    /**
     * @var string[]
     */
    protected $fillable = [
        'project_uuid',
        'chatbot_name',
        'theme_color',
        'chatbot_picture',
        'chatbot_picture_active',
        'initial_message',
        'suggest_message',
        'project_id'
    ];
}
