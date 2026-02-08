<script setup lang="ts">
import ActivityItem from '@/components/activity/ActivityItem.vue';
import type { ActivityLog } from '@/types';

defineProps<{
    activities: ActivityLog[];
}>();
</script>

<template>
    <div v-if="activities.length > 0" class="space-y-3">
        <ActivityItem
            v-for="activity in activities"
            :key="activity.id"
            :activity="activity"
        />
    </div>
    <p v-else class="text-sm text-muted-foreground">No activity yet.</p>
</template>
