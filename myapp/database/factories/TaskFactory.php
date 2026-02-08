<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['todo', 'in_progress', 'completed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'project_id' => Project::factory(),
            'assignee_id' => null,
            'creator_id' => User::factory(),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+30 days'),
            'position' => fake()->numberBetween(0, 100),
        ];
    }
}
