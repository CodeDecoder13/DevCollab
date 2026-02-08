<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_projects(): void
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    public function test_users_can_view_project_index(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $project->members()->attach($user->id, ['role' => 'owner']);

        $this->actingAs($user)
            ->get('/projects')
            ->assertOk();
    }

    public function test_users_can_create_project(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/projects', [
                'name' => 'Test Project',
                'description' => 'A test description',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('project_user', [
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }

    public function test_project_members_can_view_project(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);
        $project->members()->attach($member->id, ['role' => 'member']);

        $this->actingAs($member)
            ->get("/projects/{$project->id}")
            ->assertOk();
    }

    public function test_non_members_cannot_view_project(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($outsider)
            ->get("/projects/{$project->id}")
            ->assertForbidden();
    }

    public function test_owner_can_update_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($owner)
            ->put("/projects/{$project->id}", [
                'name' => 'Updated Name',
                'description' => 'Updated description',
                'status' => 'on_hold',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_member_cannot_update_project(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);
        $project->members()->attach($member->id, ['role' => 'member']);

        $this->actingAs($member)
            ->put("/projects/{$project->id}", ['name' => 'Hacked'])
            ->assertForbidden();
    }

    public function test_owner_can_delete_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($owner)
            ->delete("/projects/{$project->id}")
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_owner_can_archive_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($owner)
            ->patch("/projects/{$project->id}/archive")
            ->assertRedirect();

        $project->refresh();
        $this->assertNotNull($project->archived_at);
    }

    public function test_admin_can_access_any_project(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);

        $this->actingAs($admin)
            ->get("/projects/{$project->id}")
            ->assertOk();
    }
}
