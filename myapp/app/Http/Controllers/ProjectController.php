<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Project::query()
            ->with(['owner', 'members'])
            ->withCount(['tasks', 'tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed')]);

        if ($request->user() && ! $request->user()->isAdmin()) {
            $query->whereHas('members', fn ($q) => $q->where('user_id', $request->user()->id));
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('projects/Index', [
            'projects' => $projects,
            'filters' => $request->only('status'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('projects/Create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create([
            ...$request->validated(),
            'owner_id' => $request->user()->id,
        ]);

        $project->members()->attach($request->user()->id, ['role' => 'owner']);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): Response
    {
        $this->authorize('view', $project);

        $project->load(['owner', 'members', 'tasks' => function ($query) {
            $query->with(['assignee', 'creator'])->latest();
        }]);

        $project->loadCount(['tasks', 'tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed')]);

        $recentActivity = $project->activityLogs()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('projects/Show', [
            'project' => $project,
            'recentActivity' => $recentActivity,
        ]);
    }

    public function edit(Project $project): Response
    {
        $this->authorize('update', $project);

        return Inertia::render('projects/Edit', [
            'project' => $project,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function archive(Project $project)
    {
        $this->authorize('update', $project);

        $project->update([
            'archived_at' => $project->archived_at ? null : now(),
        ]);

        $message = $project->archived_at ? 'Project archived.' : 'Project unarchived.';

        return redirect()->back()->with('success', $message);
    }
}
