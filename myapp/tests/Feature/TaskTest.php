<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function createProjectWithMember(): array
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);
        $project->members()->attach($member->id, ['role' => 'member']);

        return [$project, $owner, $member];
    }

    public function test_member_can_view_tasks(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();

        $this->actingAs($member)
            ->get("/projects/{$project->id}/tasks")
            ->assertOk();
    }

    public function test_member_can_create_task(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks", [
                'title' => 'New Task',
                'description' => 'Task description',
                'priority' => 'high',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'project_id' => $project->id,
            'creator_id' => $member->id,
        ]);
    }

    public function test_non_member_cannot_create_task(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();
        $outsider = User::factory()->create();

        $this->actingAs($outsider)
            ->post("/projects/{$project->id}/tasks", ['title' => 'Hack'])
            ->assertForbidden();
    }

    public function test_task_creator_can_update_task(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'creator_id' => $member->id,
        ]);

        $this->actingAs($member)
            ->put("/projects/{$project->id}/tasks/{$task->id}", [
                'title' => 'Updated Task',
                'priority' => 'critical',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }

    public function test_task_status_can_be_updated(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'creator_id' => $owner->id,
            'status' => 'todo',
        ]);

        $this->actingAs($owner)
            ->patch("/projects/{$project->id}/tasks/{$task->id}/status", [
                'status' => 'in_progress',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_task_creator_can_delete_task(): void
    {
        [$project, $owner, $member] = $this->createProjectWithMember();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'creator_id' => $member->id,
        ]);

        $this->actingAs($member)
            ->delete("/projects/{$project->id}/tasks/{$task->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
