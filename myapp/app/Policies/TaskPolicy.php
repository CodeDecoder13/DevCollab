<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->members()->where('user_id', $user->id)->exists();
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $task->project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $project->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($task->creator_id === $user->id || $task->assignee_id === $user->id) {
            return true;
        }

        $project = $task->project;

        return $project->owner_id === $user->id
            || $project->members()
                ->where('user_id', $user->id)
                ->wherePivot('role', 'manager')
                ->exists();
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin() || $task->creator_id === $user->id) {
            return true;
        }

        return $task->project->owner_id === $user->id;
    }
}
