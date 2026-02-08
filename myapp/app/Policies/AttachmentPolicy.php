<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\User;

class AttachmentPolicy
{
    public function create(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->members()->where('user_id', $user->id)->exists();
    }

    public function view(User $user, Attachment $attachment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $attachment->task->project->members()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Attachment $attachment): bool
    {
        if ($user->isAdmin() || $attachment->user_id === $user->id) {
            return true;
        }

        return $attachment->task->project->owner_id === $user->id;
    }
}
