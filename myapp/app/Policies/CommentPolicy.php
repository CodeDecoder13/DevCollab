<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Project;
use App\Models\User;

class CommentPolicy
{
    public function create(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->isAdmin() || $comment->user_id === $user->id) {
            return true;
        }

        return $comment->task->project->owner_id === $user->id;
    }
}
