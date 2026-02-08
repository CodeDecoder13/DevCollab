<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { TaskStatus } from '@/types';

const props = defineProps<{
    status: TaskStatus;
}>();

const label = computed(() => {
    const labels: Record<TaskStatus, string> = {
        todo: 'To Do',
        in_progress: 'In Progress',
        completed: 'Completed',
    };
    return labels[props.status];
});

const variant = computed(() => {
    const variants: Record<TaskStatus, 'secondary' | 'default' | 'outline'> = {
        todo: 'secondary',
        in_progress: 'default',
        completed: 'outline',
    };
    return variants[props.status];
});
</script>

<template>
    <Badge :variant="variant">{{ label }}</Badge>
</template>
