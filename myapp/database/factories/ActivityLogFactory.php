<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject_type' => Project::class,
            'subject_id' => Project::factory(),
            'description' => fake()->sentence(),
            'event' => fake()->randomElement(['created', 'updated', 'deleted']),
            'user_id' => User::factory(),
            'properties' => null,
        ];
    }
}
