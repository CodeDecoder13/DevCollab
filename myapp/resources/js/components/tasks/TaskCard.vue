<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { CalendarDays, MessageSquare, Paperclip } from 'lucide-vue-next';
import { computed } from 'vue';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Progress } from '@/components/ui/progress';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { getInitials } from '@/composables/useInitials';
import { timeAgo } from '@/lib/utils';
import type { Task } from '@/types';

const props = defineProps<{
    task: Task;
    projectId: number;
}>();

const priorityBorderColor: Record<string, string> = {
    low: 'border-l-slate-300',
    medium: 'border-l-blue-400',
    high: 'border-l-amber-500',
    critical: 'border-l-red-500',
};

const isOverdue = computed(() => {
    if (!props.task.due_date) return false;
    return (
        new Date(props.task.due_date) < new Date() &&
        props.task.status !== 'completed'
    );
});

const formattedDate = computed(() => {
    if (!props.task.due_date) return null;
    return timeAgo(props.task.due_date);
});

const subtaskProgress = computed(() => {
    if (!props.task.subtasks_count) return null;
    return Math.round(
        ((props.task.completed_subtasks_count ?? 0) / props.task.subtasks_count) * 100,
    );
});

function updateStatus(status: string) {
    router.patch(
        `/projects/${props.projectId}/tasks/${props.task.id}/status`,
        { status },
        { preserveState: true, preserveScroll: true },
    );
}

function updatePriority(priority: string) {
    router.patch(
        `/projects/${props.projectId}/tasks/${props.task.id}`,
        { priority },
        { preserveState: true, preserveScroll: true },
    );
}
</script>

<template>
    <Link :href="`/projects/${projectId}/tasks/${task.id}`">
        <Card
            class="group cursor-pointer border-l-4 transition-shadow hover:shadow-md"
            :class="priorityBorderColor[task.priority] ?? 'border-l-slate-300'"
        >
            <CardHeader class="p-3 pb-1">
                <div class="flex items-start justify-between gap-2">
                    <TooltipProvider v-if="task.description">
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <p class="text-sm leading-tight font-medium">
                                    {{ task.title }}
                                </p>
                            </TooltipTrigger>
                            <TooltipContent side="top" class="max-w-xs">
                                <p class="text-xs">{{ task.description?.slice(0, 100) }}{{ task.description && task.description.length > 100 ? '...' : '' }}</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    <p v-else class="text-sm leading-tight font-medium">
                        {{ task.title }}
                    </p>
                    <TaskPriorityBadge :priority="task.priority" />
                </div>
                <div v-if="task.labels && task.labels.length > 0" class="mt-1 flex flex-wrap gap-1">
                    <span
                        v-for="label in task.labels"
                        :key="label.id"
                        class="h-2 w-2 rounded-full"
                        :style="{ backgroundColor: label.color }"
                        :title="label.name"
                    />
                </div>
            </CardHeader>
            <CardContent class="p-3 pt-1">
                <div v-if="subtaskProgress !== null" class="mb-2">
                    <div class="flex items-center gap-2 text-xs text-muted-foreground">
                        <span>{{ task.completed_subtasks_count }}/{{ task.subtasks_count }} subtasks</span>
                    </div>
                    <Progress :model-value="subtaskProgress" class="mt-1 h-1" />
                </div>
                <div class="flex items-center gap-3">
                    <div
                        v-if="formattedDate"
                        class="flex items-center gap-1 text-xs"
                        :class="isOverdue ? 'text-destructive' : 'text-muted-foreground'"
                    >
                        <CalendarDays class="h-3 w-3" />
                        {{ formattedDate }}
                    </div>
                    <div
                        v-if="task.comments_count"
                        class="flex items-center gap-1 text-xs text-muted-foreground"
                    >
                        <MessageSquare class="h-3 w-3" />
                        {{ task.comments_count }}
                    </div>
                    <div
                        v-if="task.attachments_count"
                        class="flex items-center gap-1 text-xs text-muted-foreground"
                    >
                        <Paperclip class="h-3 w-3" />
                        {{ task.attachments_count }}
                    </div>
                    <div class="ml-auto flex items-center gap-1">
                        <div class="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                            <DropdownMenu>
                                <DropdownMenuTrigger
                                    class="rounded bg-muted px-1.5 py-0.5 text-[10px]"
                                    @click.stop.prevent
                                >
                                    Status
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem @click.stop.prevent="updateStatus('todo')">To Do</DropdownMenuItem>
                                    <DropdownMenuItem @click.stop.prevent="updateStatus('in_progress')">In Progress</DropdownMenuItem>
                                    <DropdownMenuItem @click.stop.prevent="updateStatus('completed')">Completed</DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                            <DropdownMenu>
                                <DropdownMenuTrigger
                                    class="rounded bg-muted px-1.5 py-0.5 text-[10px]"
                                    @click.stop.prevent
                                >
                                    Priority
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem @click.stop.prevent="updatePriority('low')">Low</DropdownMenuItem>
                                    <DropdownMenuItem @click.stop.prevent="updatePriority('medium')">Medium</DropdownMenuItem>
                                    <DropdownMenuItem @click.stop.prevent="updatePriority('high')">High</DropdownMenuItem>
                                    <DropdownMenuItem @click.stop.prevent="updatePriority('critical')">Critical</DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                        <Avatar v-if="task.assignee" class="h-6 w-6">
                            <AvatarImage
                                v-if="task.assignee.avatar"
                                :src="task.assignee.avatar"
                            />
                            <AvatarFallback class="text-[10px]">{{
                                getInitials(task.assignee.name)
                            }}</AvatarFallback>
                        </Avatar>
                    </div>
                </div>
            </CardContent>
        </Card>
    </Link>
</template>
