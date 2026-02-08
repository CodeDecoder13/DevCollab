<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\ActivityLogger;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $task = $comment->task;
        ActivityLogger::log($task, "Comment added to task \"{$task->title}\"", 'comment_added');
    }

    public function deleted(Comment $comment): void
    {
        $task = $comment->task;
        ActivityLogger::log($task, "Comment removed from task \"{$task->title}\"", 'comment_deleted');
    }
}
