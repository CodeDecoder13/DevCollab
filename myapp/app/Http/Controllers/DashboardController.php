<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $projectQuery = Project::query()
            ->with(['owner', 'members'])
            ->withCount(['tasks', 'tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed')]);

        if (! $user->isAdmin()) {
            $projectQuery->whereHas('members', fn ($q) => $q->where('user_id', $user->id));
        }

        $projects = $projectQuery->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $myTasks = Task::with(['project', 'creator'])
            ->where('assignee_id', $user->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC')
            ->take(10)
            ->get();

        $taskStats = [
            'todo' => Task::where('assignee_id', $user->id)->where('status', 'todo')->count(),
            'in_progress' => Task::where('assignee_id', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('assignee_id', $user->id)->where('status', 'completed')->count(),
        ];

        $overdueCount = Task::where('assignee_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();

        $overdueTasks = Task::with('project:id,name')
            ->where('assignee_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $dueSoonCount = Task::where('assignee_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->count();

        $projectIds = $user->isAdmin()
            ? Project::pluck('id')
            : $user->projects()->pluck('projects.id');

        $recentActivity = ActivityLog::with('user')
            ->where(function ($query) use ($projectIds) {
                $query->where(function ($q) use ($projectIds) {
                    $q->where('subject_type', Project::class)
                        ->whereIn('subject_id', $projectIds);
                })->orWhere(function ($q) use ($projectIds) {
                    $q->where('subject_type', Task::class)
                        ->whereIn('subject_id', Task::whereIn('project_id', $projectIds)->pluck('id'));
                });
            })
            ->latest()
            ->take(15)
            ->get();

        return Inertia::render('Dashboard', [
            'projects' => $projects,
            'myTasks' => $myTasks,
            'taskStats' => $taskStats,
            'recentActivity' => $recentActivity,
            'overdueCount' => $overdueCount,
            'overdueTasks' => $overdueTasks,
            'dueSoonCount' => $dueSoonCount,
        ]);
    }
}
