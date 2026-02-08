<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(Model $subject, string $description, string $event, ?array $properties = null): ActivityLog
    {
        return ActivityLog::create([
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'description' => $description,
            'event' => $event,
            'user_id' => auth()->id(),
            'properties' => $properties,
        ]);
    }
}
