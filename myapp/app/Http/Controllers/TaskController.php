<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(Request $request, Project $project): Response
    {
        $this->authorize('viewAny', [Task::class, $project]);

        $tasks = $project->tasks()
            ->whereNull('parent_task_id')
            ->with(['assignee', 'creator', 'labels'])
            ->withCount(['comments', 'attachments', 'subtasks', 'subtasks as completed_subtasks_count' => fn ($q) => $q->where('status', 'completed')])
            ->when($request->priority, fn ($q, $priority) => $q->where('priority', $priority))
            ->when($request->assignee_id, fn ($q, $id) => $q->where('assignee_id', $id))
            ->orderBy('position')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('projects/tasks/Index', [
            'project' => $project->load('members'),
            'tasks' => $tasks,
            'filters' => $request->only(['priority', 'assignee_id']),
        ]);
    }

    public function create(Project $project): Response
    {
        $this->authorize('create', [Task::class, $project]);

        return Inertia::render('projects/tasks/Create', [
            'project' => $project->load('members'),
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $project->tasks()->create([
            ...$request->validated(),
            'creator_id' => $request->user()->id,
        ]);

        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Task created successfully.');
    }

    public function quickStore(Request $request, Project $project)
    {
        $this->authorize('create', [Task::class, $project]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,completed',
            'parent_task_id' => 'nullable|exists:tasks,id',
        ]);

        $project->tasks()->create([
            ...$validated,
            'priority' => 'medium',
            'creator_id' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Task created.');
    }

    public function show(Project $project, Task $task): Response
    {
        $this->authorize('view', $task);

        $task->load([
            'assignee',
            'creator',
            'labels',
            'subtasks' => fn ($q) => $q->with('assignee')->orderBy('created_at'),
            'comments' => fn ($q) => $q->with('user')->latest(),
            'attachments' => fn ($q) => $q->with('user')->latest(),
        ]);

        return Inertia::render('projects/tasks/Show', [
            'project' => $project->load(['members', 'labels']),
            'task' => $task,
        ]);
    }

    public function edit(Project $project, Task $task): Response
    {
        $this->authorize('update', $task);

        return Inertia::render('projects/tasks/Edit', [
            'project' => $project->load('members'),
            'task' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Project $project, Task $task)
    {
        $task->update($request->validated());

        return redirect()->back()->with('success', 'Task status updated.');
    }
}
