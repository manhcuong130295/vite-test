<?php

namespace App\Models\Traits\Relationships;

use App\Models\ChatHistory;
use App\Models\LineChannels;
use App\Models\ProjectContent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

trait ProjectRelationship
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     * @return HasMany
     */
    public function chatHistories(): HasMany
    {
        return $this->hasMany(ChatHistory::class);
    }

    /**
     * @return HasMany
     */
    public function projectContents(): HasMany
    {
        return $this->hasMany(ProjectContent::class);
    }
}
