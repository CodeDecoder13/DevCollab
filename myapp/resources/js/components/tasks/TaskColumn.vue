<script setup lang="ts">
import draggable from 'vuedraggable';
import TaskCard from '@/components/tasks/TaskCard.vue';
import type { Task } from '@/types';

defineProps<{
    title: string;
    tasks: Task[];
    projectId: number;
}>();

defineEmits<{
    change: [
        event: {
            added?: { element: Task };
            removed?: { element: Task };
            moved?: { element: Task };
        },
    ];
}>();
</script>

<template>
    <div
        class="flex min-h-[200px] w-full flex-col rounded-lg bg-muted/50 p-2 md:w-80"
    >
        <div class="mb-2 flex items-center justify-between px-1">
            <h3 class="text-sm font-semibold">{{ title }}</h3>
            <span
                class="rounded-full bg-muted px-2 py-0.5 text-xs font-medium"
                >{{ tasks.length }}</span
            >
        </div>
        <draggable
            :list="tasks"
            group="tasks"
            item-key="id"
            class="flex flex-1 flex-col gap-2"
            ghost-class="opacity-50"
            @change="$emit('change', $event)"
        >
            <template #item="{ element }">
                <TaskCard :task="element" :project-id="projectId" />
            </template>
        </draggable>
    </div>
</template>
