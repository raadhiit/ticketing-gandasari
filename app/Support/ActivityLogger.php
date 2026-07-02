<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        ?User $user = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $properties = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
            'created_at' => now(),
        ]);
    }
}
