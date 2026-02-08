<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
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

    public function test_member_can_upload_attachment(): void
    {
        Storage::fake('local');
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks/{$task->id}/attachments", [
                'file' => $file,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('attachments', [
            'task_id' => $task->id,
            'user_id' => $member->id,
            'filename' => 'document.pdf',
        ]);
    }

    public function test_member_can_download_attachment(): void
    {
        Storage::fake('local');
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        Storage::disk('local')->put('attachments/test.pdf', 'content');
        $attachment = Attachment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $member->id,
            'path' => 'attachments/test.pdf',
            'filename' => 'test.pdf',
        ]);

        $this->actingAs($member)
            ->get("/attachments/{$attachment->id}")
            ->assertOk();
    }

    public function test_uploader_can_delete_attachment(): void
    {
        Storage::fake('local');
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        Storage::disk('local')->put('attachments/test.pdf', 'content');
        $attachment = Attachment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $member->id,
            'path' => 'attachments/test.pdf',
        ]);

        $this->actingAs($member)
            ->delete("/attachments/{$attachment->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
    }

    public function test_file_size_limit_is_enforced(): void
    {
        Storage::fake('local');
        [$project, $task, $owner, $member] = $this->createProjectWithTask();

        $file = UploadedFile::fake()->create('large.pdf', 11000); // > 10MB

        $this->actingAs($member)
            ->post("/projects/{$project->id}/tasks/{$task->id}/attachments", [
                'file' => $file,
            ])
            ->assertSessionHasErrors('file');
    }
}
