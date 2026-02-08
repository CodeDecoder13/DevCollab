<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $users = User::factory(5)->create();

        $projects = Project::factory(3)->create([
            'owner_id' => $admin->id,
        ]);

        foreach ($projects as $project) {
            $project->members()->attach($admin->id, ['role' => 'owner']);

            $memberUsers = $users->random(rand(2, 4));
            foreach ($memberUsers as $index => $user) {
                $role = $index === 0 ? 'manager' : 'member';
                $project->members()->attach($user->id, ['role' => $role]);
            }

            $projectMembers = $project->members;

            $tasks = Task::factory(rand(5, 10))->create([
                'project_id' => $project->id,
                'creator_id' => $admin->id,
                'assignee_id' => $projectMembers->random()->id,
            ]);

            foreach ($tasks->random(min(3, $tasks->count())) as $task) {
                Comment::factory(rand(1, 3))->create([
                    'task_id' => $task->id,
                    'user_id' => $projectMembers->random()->id,
                ]);
            }
        }
    }
}
