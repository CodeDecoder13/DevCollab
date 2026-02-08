<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [\App\Models\Task::class, $this->route('project')]);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'status' => 'sometimes|in:todo,in_progress,completed',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'parent_task_id' => 'nullable|exists:tasks,id',
        ];
    }
}
