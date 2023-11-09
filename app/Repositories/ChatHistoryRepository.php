<?php

namespace App\Repositories;

use App\Models\ChatHistory;
use Illuminate\Database\Eloquent\Collection;

class ChatHistoryRepository
{
    /**
     * Chat history project.
     */
    public function projectChatHistory(string $projectId): Collection
    {
        return ChatHistory::query()->where('project_id', $projectId)->latest('created_at')->take(10)->get();
    }
}