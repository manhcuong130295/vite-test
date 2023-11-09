<?php

namespace App\Models\Traits\Relationships;

use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait LineChannelRelationship
{
    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
