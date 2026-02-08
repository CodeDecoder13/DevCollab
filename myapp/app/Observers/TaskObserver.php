<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\ActivityLogger;

class TaskObserver
{
    public function created(Task $task): void
    {
        ActivityLogger::log($task, "Task \"{$task->title}\" was created", 'created');
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged('status')) {
            ActivityLogger::log($task, "Task \"{$task->title}\" status changed to {$task->status->value}", 'status_changed', [
                'old_status' => $task->getOriginal('status'),
                'new_status' => $task->status->value,
            ]);

            return;
        }

        if ($task->wasChanged('assignee_id')) {
            $assignee = $task->assignee;
            $name = $assignee ? $assignee->name : 'nobody';
            ActivityLogger::log($task, "Task \"{$task->title}\" was assigned to {$name}", 'assigned', [
                'assignee_id' => $task->assignee_id,
            ]);

            return;
        }

        ActivityLogger::log($task, "Task \"{$task->title}\" was updated", 'updated', [
            'changed' => $task->getChanges(),
        ]);
    }

    public function deleted(Task $task): void
    {
        ActivityLogger::log($task, "Task \"{$task->title}\" was deleted", 'deleted');
    }
}
