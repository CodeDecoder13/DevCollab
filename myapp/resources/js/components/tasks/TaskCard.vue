<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CalendarDays, MessageSquare, Paperclip } from 'lucide-vue-next';
import { computed } from 'vue';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { getInitials } from '@/composables/useInitials';
import type { Task } from '@/types';

const props = defineProps<{
    task: Task;
    projectId: number;
}>();

const isOverdue = computed(() => {
    if (!props.task.due_date) return false;
    return (
        new Date(props.task.due_date) < new Date() &&
        props.task.status !== 'completed'
    );
});

const formattedDate = computed(() => {
    if (!props.task.due_date) return null;
    return new Date(props.task.due_date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
});
</script>

<template>
    <Link :href="`/projects/${projectId}/tasks/${task.id}`">
        <Card class="cursor-pointer transition-colors hover:bg-accent/50">
            <CardHeader class="p-3 pb-1">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm leading-tight font-medium">
                        {{ task.title }}
                    </p>
                    <TaskPriorityBadge :priority="task.priority" />
                </div>
            </CardHeader>
            <CardContent class="flex items-center gap-3 p-3 pt-1">
                <div
                    v-if="formattedDate"
                    class="flex items-center gap-1 text-xs"
                    :class="
                        isOverdue ? 'text-destructive' : 'text-muted-foreground'
                    "
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
                <div class="ml-auto">
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
            </CardContent>
        </Card>
    </Link>
</template>
