<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import TaskColumn from '@/components/tasks/TaskColumn.vue';
import type { Task, TaskStatus } from '@/types';

const props = defineProps<{
    tasks: Task[];
    projectId: number;
}>();

const todoTasks = ref<Task[]>([]);
const inProgressTasks = ref<Task[]>([]);
const completedTasks = ref<Task[]>([]);

function splitTasks() {
    todoTasks.value = props.tasks.filter((t) => t.status === 'todo');
    inProgressTasks.value = props.tasks.filter(
        (t) => t.status === 'in_progress',
    );
    completedTasks.value = props.tasks.filter((t) => t.status === 'completed');
}

splitTasks();
watch(() => props.tasks, splitTasks);

function handleChange(
    status: TaskStatus,
    event: { added?: { element: Task } },
) {
    if (event.added) {
        const task = event.added.element;
        router.patch(
            `/projects/${props.projectId}/tasks/${task.id}/status`,
            { status },
            { preserveState: true, preserveScroll: true },
        );
    }
}

const columns = computed(() => [
    { title: 'To Do', status: 'todo' as TaskStatus, tasks: todoTasks.value },
    {
        title: 'In Progress',
        status: 'in_progress' as TaskStatus,
        tasks: inProgressTasks.value,
    },
    {
        title: 'Completed',
        status: 'completed' as TaskStatus,
        tasks: completedTasks.value,
    },
]);
</script>

<template>
    <div class="flex flex-col gap-4 overflow-x-auto md:flex-row">
        <TaskColumn
            v-for="col in columns"
            :key="col.status"
            :title="col.title"
            :tasks="col.tasks"
            :project-id="projectId"
            :status="col.status"
            @change="handleChange(col.status, $event)"
        />
    </div>
</template>
