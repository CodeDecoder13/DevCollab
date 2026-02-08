<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { CalendarDays } from 'lucide-vue-next';
import { computed } from 'vue';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { timeAgo } from '@/lib/utils';
import type { Task } from '@/types';

const props = defineProps<{
    tasks: Task[];
}>();

const priorityBorderColor: Record<string, string> = {
    low: 'border-l-slate-300',
    medium: 'border-l-blue-400',
    high: 'border-l-amber-500',
    critical: 'border-l-red-500',
};

const groupedTasks = computed(() => {
    const groups: Record<string, Task[]> = {};
    for (const task of props.tasks) {
        const name = task.project?.name ?? 'Unknown';
        if (!groups[name]) groups[name] = [];
        groups[name].push(task);
    }
    return groups;
});

function isOverdue(dueDate: string | null): boolean {
    if (!dueDate) return false;
    return new Date(dueDate) < new Date();
}

function updateStatus(task: Task, status: string) {
    router.patch(`/projects/${task.project_id}/tasks/${task.id}/status`, { status }, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>My Tasks</CardTitle>
        </CardHeader>
        <CardContent>
            <div v-if="tasks.length > 0" class="space-y-4">
                <div v-for="(projectTasks, projectName) in groupedTasks" :key="projectName">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                        {{ projectName }}
                    </p>
                    <div class="space-y-1">
                        <div
                            v-for="task in projectTasks"
                            :key="task.id"
                            class="group flex items-center justify-between rounded-md border border-l-4 p-3 transition-colors hover:bg-accent/50"
                            :class="priorityBorderColor[task.priority] ?? 'border-l-slate-300'"
                        >
                            <Link
                                :href="`/projects/${task.project_id}/tasks/${task.id}`"
                                class="flex-1 space-y-1"
                            >
                                <p class="text-sm font-medium">{{ task.title }}</p>
                            </Link>
                            <div class="flex items-center gap-2">
                                <div
                                    v-if="task.due_date"
                                    class="flex items-center gap-1 text-xs"
                                    :class="isOverdue(task.due_date) ? 'text-destructive' : 'text-muted-foreground'"
                                >
                                    <CalendarDays class="h-3 w-3" />
                                    {{ timeAgo(task.due_date) }}
                                </div>
                                <TaskPriorityBadge :priority="task.priority" />
                                <DropdownMenu>
                                    <DropdownMenuTrigger class="rounded px-2 py-0.5 text-xs opacity-0 transition-opacity hover:bg-muted group-hover:opacity-100">
                                        {{ task.status === 'todo' ? 'To Do' : task.status === 'in_progress' ? 'In Progress' : 'Done' }}
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent>
                                        <DropdownMenuItem @click="updateStatus(task, 'todo')">To Do</DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(task, 'in_progress')">In Progress</DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(task, 'completed')">Completed</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else class="text-sm text-muted-foreground">
                No tasks assigned to you.
            </p>
        </CardContent>
    </Card>
</template>
