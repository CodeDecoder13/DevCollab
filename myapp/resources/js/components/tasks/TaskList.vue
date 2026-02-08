<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import TaskStatusBadge from '@/components/tasks/TaskStatusBadge.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { getInitials } from '@/composables/useInitials';
import type { Task } from '@/types';

defineProps<{
    tasks: Task[];
    projectId: number;
}>();
</script>

<template>
    <div class="rounded-md border">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Title</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Priority</TableHead>
                    <TableHead>Assignee</TableHead>
                    <TableHead>Due Date</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="task in tasks" :key="task.id">
                    <TableCell>
                        <Link
                            :href="`/projects/${projectId}/tasks/${task.id}`"
                            class="font-medium hover:underline"
                        >
                            {{ task.title }}
                        </Link>
                    </TableCell>
                    <TableCell>
                        <TaskStatusBadge :status="task.status" />
                    </TableCell>
                    <TableCell>
                        <TaskPriorityBadge :priority="task.priority" />
                    </TableCell>
                    <TableCell>
                        <div
                            v-if="task.assignee"
                            class="flex items-center gap-2"
                        >
                            <Avatar class="h-6 w-6">
                                <AvatarImage
                                    v-if="task.assignee.avatar"
                                    :src="task.assignee.avatar"
                                />
                                <AvatarFallback class="text-[10px]">{{
                                    getInitials(task.assignee.name)
                                }}</AvatarFallback>
                            </Avatar>
                            <span class="text-sm">{{
                                task.assignee.name
                            }}</span>
                        </div>
                        <span v-else class="text-sm text-muted-foreground"
                            >Unassigned</span
                        >
                    </TableCell>
                    <TableCell>
                        <span v-if="task.due_date" class="text-sm">
                            {{ new Date(task.due_date).toLocaleDateString() }}
                        </span>
                        <span v-else class="text-sm text-muted-foreground"
                            >No date</span
                        >
                    </TableCell>
                </TableRow>
                <TableRow v-if="tasks.length === 0">
                    <TableCell
                        colspan="5"
                        class="py-8 text-center text-muted-foreground"
                    >
                        No tasks found.
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
