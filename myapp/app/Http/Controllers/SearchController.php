<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['projects' => [], 'tasks' => [], 'users' => []]);
        }

        $user = $request->user();

        $projectQuery = Project::query()
            ->where('name', 'ilike', "%{$query}%");

        if (! $user->isAdmin()) {
            $projectQuery->whereHas('members', fn ($q) => $q->where('user_id', $user->id));
        }

        $projects = $projectQuery->limit(5)->get(['id', 'name', 'status']);

        $taskQuery = Task::query()
            ->with('project:id,name')
            ->where('title', 'ilike', "%{$query}%");

        if (! $user->isAdmin()) {
            $taskQuery->whereHas('project.members', fn ($q) => $q->where('user_id', $user->id));
        }

        $tasks = $taskQuery->limit(5)->get(['id', 'title', 'status', 'project_id']);

        $users = User::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                    ->orWhere('email', 'ilike', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'name', 'email']);

        return response()->json([
            'projects' => $projects,
            'tasks' => $tasks,
            'users' => $users,
        ]);
    }
}
