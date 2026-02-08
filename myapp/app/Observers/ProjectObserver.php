<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\ActivityLogger;

class ProjectObserver
{
    public function created(Project $project): void
    {
        ActivityLogger::log($project, "Project \"{$project->name}\" was created", 'created');
    }

    public function updated(Project $project): void
    {
        if ($project->wasChanged('archived_at')) {
            $event = $project->archived_at ? 'archived' : 'unarchived';
            ActivityLogger::log($project, "Project \"{$project->name}\" was {$event}", $event);

            return;
        }

        ActivityLogger::log($project, "Project \"{$project->name}\" was updated", 'updated', [
            'changed' => $project->getChanges(),
        ]);
    }

    public function deleted(Project $project): void
    {
        ActivityLogger::log($project, "Project \"{$project->name}\" was deleted", 'deleted');
    }
}
