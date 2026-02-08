<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CalendarDays } from 'lucide-vue-next';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import TaskStatusBadge from '@/components/tasks/TaskStatusBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Task } from '@/types';

defineProps<{
    tasks: Task[];
}>();

function isOverdue(dueDate: string | null): boolean {
    if (!dueDate) return false;
    return new Date(dueDate) < new Date();
}
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>My Tasks</CardTitle>
        </CardHeader>
        <CardContent>
            <div v-if="tasks.length > 0" class="space-y-3">
                <Link
                    v-for="task in tasks"
                    :key="task.id"
                    :href="`/projects/${task.project_id}/tasks/${task.id}`"
                    class="flex items-center justify-between rounded-md border p-3 transition-colors hover:bg-accent/50"
                >
                    <div class="space-y-1">
                        <p class="text-sm font-medium">{{ task.title }}</p>
                        <p class="text-xs text-muted-foreground">
                            {{ task.project?.name }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div
                            v-if="task.due_date"
                            class="flex items-center gap-1 text-xs"
                            :class="
                                isOverdue(task.due_date)
                                    ? 'text-destructive'
                                    : 'text-muted-foreground'
                            "
                        >
                            <CalendarDays class="h-3 w-3" />
                            {{
                                new Date(task.due_date).toLocaleDateString(
                                    'en-US',
                                    { month: 'short', day: 'numeric' },
                                )
                            }}
                        </div>
                        <TaskPriorityBadge :priority="task.priority" />
                        <TaskStatusBadge :status="task.status" />
                    </div>
                </Link>
            </div>
            <p v-else class="text-sm text-muted-foreground">
                No tasks assigned to you.
            </p>
        </CardContent>
    </Card>
</template>
