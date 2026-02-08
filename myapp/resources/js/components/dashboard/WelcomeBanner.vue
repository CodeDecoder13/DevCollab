<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    overdueCount: number;
}>();

const page = usePage();
const userName = computed(() => page.props.auth.user?.name ?? 'there');

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good morning';
    if (hour < 17) return 'Good afternoon';
    return 'Good evening';
});
</script>

<template>
    <div class="rounded-lg bg-gradient-to-r from-primary/10 via-primary/5 to-transparent p-6">
        <h1 class="text-2xl font-bold">{{ greeting }}, {{ userName }}</h1>
        <p class="mt-1 text-muted-foreground">
            <template v-if="overdueCount > 0">
                You have <span class="font-medium text-destructive">{{ overdueCount }} overdue task{{ overdueCount > 1 ? 's' : '' }}</span> that need attention.
            </template>
            <template v-else>
                You're all caught up! Keep up the great work.
            </template>
        </p>
    </div>
</template>
