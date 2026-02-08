<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentAdded extends Notification
{
    use Queueable;

    public function __construct(
        protected Comment $comment,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        $task = $this->comment->task;

        return [
            'task_id' => $task->id,
            'task_title' => $task->title,
            'project_id' => $task->project_id,
            'project_name' => $task->project->name,
            'commenter_name' => $this->comment->user->name,
            'message' => "{$this->comment->user->name} commented on \"{$task->title}\"",
        ];
    }
}
