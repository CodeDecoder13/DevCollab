<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { timeAgo } from '@/lib/utils';
import type { ActivityLog } from '@/types';

defineProps<{
    activity: ActivityLog;
}>();
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
