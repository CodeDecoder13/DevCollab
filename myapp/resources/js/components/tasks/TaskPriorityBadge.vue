<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { TaskPriority } from '@/types';

const props = defineProps<{
    priority: TaskPriority;
}>();

const label = computed(() => {
    const labels: Record<TaskPriority, string> = {
        low: 'Low',
        medium: 'Medium',
        high: 'High',
        critical: 'Critical',
    };
    return labels[props.priority];
});

const colorClass = computed(() => {
    const classes: Record<TaskPriority, string> = {
        low: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        high: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        critical: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };
    return classes[props.priority];
});
</script>

<template>
    <Badge variant="outline" :class="colorClass">{{ label }}</Badge>
</template>
