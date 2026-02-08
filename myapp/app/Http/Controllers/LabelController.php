<?php

namespace App\Http\Controllers;

use App\Http\Requests\Label\StoreLabelRequest;
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;

class LabelController extends Controller
{
    public function store(StoreLabelRequest $request, Project $project)
    {
        $project->labels()->create($request->validated());

        return redirect()->back()->with('success', 'Label created.');
    }

    public function destroy(Project $project, Label $label)
    {
        $label->delete();

        return redirect()->back()->with('success', 'Label deleted.');
    }

    public function toggle(Project $project, Task $task, Label $label)
    {
        $task->labels()->toggle($label->id);

        return redirect()->back()->with('success', 'Label toggled.');
    }
}
