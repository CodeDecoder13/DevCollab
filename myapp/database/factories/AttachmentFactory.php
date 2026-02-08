<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'filename' => fake()->word().'.'.fake()->fileExtension(),
            'path' => 'attachments/'.fake()->uuid().'.'.fake()->fileExtension(),
            'mime_type' => fake()->mimeType(),
            'size' => fake()->numberBetween(1024, 10485760),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
