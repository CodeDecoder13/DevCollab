<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\CommentAdded;
use App\Services\ActivityLogger;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $task = $comment->task;
        ActivityLogger::log($task, "Comment added to task \"{$task->title}\"", 'comment_added');

        $notifyUsers = collect();

        if ($task->assignee_id && $task->assignee_id !== $comment->user_id) {
            $notifyUsers->push($task->assignee);
        }

        if ($task->creator_id !== $comment->user_id && $task->creator_id !== $task->assignee_id) {
            $notifyUsers->push($task->creator);
        }

        $notifyUsers->filter()->each(function ($user) use ($comment) {
            $user->notify(new CommentAdded($comment));
        });
    }

    public function deleted(Comment $comment): void
    {
        $task = $comment->task;
        ActivityLogger::log($task, "Comment removed from task \"{$task->title}\"", 'comment_deleted');
    }
}
