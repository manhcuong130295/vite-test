<?php

namespace App\Models\Traits\Relationships;

use App\Models\Project;

trait FacebookFanpageRelationship
{
    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}