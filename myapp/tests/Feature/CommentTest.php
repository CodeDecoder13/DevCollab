<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function createProjectWithTask(): array
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $project->members()->attach($owner->id, ['role' => 'owner']);
        $project->members()->attach($member->id, ['role' => 'member']);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'creator_id' => $owner->id,
        ]);

        return [$project, $task, $owner, $member];
    }

    public function test_member_can_add_comment(): void
    {
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks/{$task->id}/comments", [
                'body' => 'Great work!',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'body' => 'Great work!',
            'task_id' => $task->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_author_can_update_comment(): void
    {
        [$project, $task, $owner, $member] = $this->createProjectWithTask();
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $member->id,
            'body' => 'Original',
        ]);

        $this->actingAs($member)
            ->put("/comments/{$comment->id}", ['body' => 'Updated'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated',
        ]);
    }

    public function test_non_author_cannot_update_comment(): void
    {
        [$project, $task, $owner, $member] = $this->createProjectWithTask();
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($owner)
            ->put("/comments/{$comment->id}", ['body' => 'Hacked'])
            ->assertForbidden();
    }

    public function test_author_can_delete_comment(): void
    {
        [$project, $task, $owner, $member] = $this->createProjectWithTask();
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($member)
            ->delete("/comments/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_comment_body_is_required(): void
    {
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks/{$task->id}/comments", ['body' => ''])
            ->assertSessionHasErrors('body');
    }
}
