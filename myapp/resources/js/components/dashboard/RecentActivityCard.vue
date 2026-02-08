<script setup lang="ts">
import ActivityFeed from '@/components/activity/ActivityFeed.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ActivityLog } from '@/types';

defineProps<{
    activities: ActivityLog[];
}>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Recent Activity</CardTitle>
        </CardHeader>
        <CardContent>
            <ActivityFeed :activities="activities" />
        </CardContent>
    </Card>
</template>
