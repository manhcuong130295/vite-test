<?php

namespace App\Models\Traits\Relationships;

use App\Models\ChatInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Project;

trait ChatInterfaceRelationship
{
    /**s
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
