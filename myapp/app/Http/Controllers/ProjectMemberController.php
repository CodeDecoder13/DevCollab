<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreMemberRequest;
use App\Http\Requests\Project\UpdateMemberRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectMemberController extends Controller
{
    public function index(Project $project, Request $request): Response
    {
        $this->authorize('view', $project);

        $project->load('members');

        $availableUsers = User::whereNotIn('id', $project->members->pluck('id'))
            ->when($request->search, fn ($query, $search) => $query->where('name', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%"))
            ->limit(10)
            ->get();

        return Inertia::render('projects/Members', [
            'project' => $project,
            'availableUsers' => $availableUsers,
        ]);
    }

    public function store(StoreMemberRequest $request, Project $project)
    {
        $userId = $request->validated()['user_id'];

        if ($project->members()->where('user_id', $userId)->exists()) {
            return redirect()->back()->with('error', 'User is already a member.');
        }

        $project->members()->attach($userId, [
            'role' => $request->validated()['role'] ?? 'member',
        ]);

        return redirect()->back()->with('success', 'Member added successfully.');
    }

    public function update(UpdateMemberRequest $request, Project $project, User $user)
    {
        if ($project->owner_id === $user->id) {
            return redirect()->back()->with('error', 'Cannot change the owner\'s role.');
        }

        $project->members()->updateExistingPivot($user->id, [
            'role' => $request->validated()['role'],
        ]);

        return redirect()->back()->with('success', 'Member role updated.');
    }

    public function destroy(Project $project, User $user)
    {
        $this->authorize('manageMembers', $project);

        if ($project->owner_id === $user->id) {
            return redirect()->back()->with('error', 'Cannot remove the project owner.');
        }

        $project->members()->detach($user->id);

        return redirect()->back()->with('success', 'Member removed.');
    }
}
