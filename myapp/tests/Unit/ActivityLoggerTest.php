<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLoggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_activity_log(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $log = ActivityLogger::log($project, 'Test action', 'test', ['key' => 'value']);

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => Project::class,
            'subject_id' => $project->id,
            'description' => 'Test action',
            'event' => 'test',
            'user_id' => $user->id,
        ]);

        $this->assertEquals(['key' => 'value'], $log->properties);
    }

    public function test_project_observer_logs_creation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'My Project',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => Project::class,
            'subject_id' => $project->id,
            'event' => 'created',
        ]);
    }
}
