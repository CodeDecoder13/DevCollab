<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { ProjectStatus } from '@/types';

const props = defineProps<{
    status: ProjectStatus;
}>();

const label = computed(() => {
    const labels: Record<ProjectStatus, string> = {
        active: 'Active',
        on_hold: 'On Hold',
        completed: 'Completed',
    };
    return labels[props.status];
});

const variant = computed(() => {
    const variants: Record<ProjectStatus, 'default' | 'secondary' | 'outline'> =
        {
            active: 'default',
            on_hold: 'secondary',
            completed: 'outline',
        };
    return variants[props.status];
});
</script>

<template>
    <Badge :variant="variant">{{ label }}</Badge>
</template>
