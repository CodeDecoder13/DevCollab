<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import type { ActivityLog } from '@/types';

defineProps<{
    activity: ActivityLog;
}>();

function timeAgo(dateStr: string): string {
    const date = new Date(dateStr);
    const now = new Date();
    const seconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (seconds < 60) return 'just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
    if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
    return date.toLocaleDateString();
}
</script>

<template>
    <div class="flex items-start gap-3">
        <Avatar class="mt-0.5 h-6 w-6">
            <AvatarImage
                v-if="activity.user?.avatar"
                :src="activity.user.avatar"
            />
            <AvatarFallback class="text-[10px]">{{
                getInitials(activity.user?.name)
            }}</AvatarFallback>
        </Avatar>
        <div class="flex-1">
            <p class="text-sm">{{ activity.description }}</p>
            <p class="text-xs text-muted-foreground">
                {{ activity.user?.name ?? 'System' }} &middot;
                {{ timeAgo(activity.created_at) }}
            </p>
        </div>
    </div>
</template>
