<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Bell, Check, CheckCheck } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { timeAgo } from '@/lib/utils';
import type { AppNotification } from '@/types';

const props = defineProps<{
    unreadCount: number;
}>();

const notifications = ref<AppNotification[]>([]);
const loading = ref(false);
const open = ref(false);

watch(open, async (isOpen) => {
    if (isOpen) {
        loading.value = true;
        try {
            const response = await fetch('/notifications', {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            notifications.value = await response.json();
        } catch {
            notifications.value = [];
        }
        loading.value = false;
    }
});

function markAsRead(notification: AppNotification) {
    router.patch(`/notifications/${notification.id}/read`, {}, {
        preserveState: true,
        preserveScroll: true,
    });
    notification.read_at = new Date().toISOString();
}

function markAllAsRead() {
    router.post('/notifications/read-all', {}, {
        preserveState: true,
        preserveScroll: true,
    });
    notifications.value.forEach((n) => {
        n.read_at = new Date().toISOString();
    });
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button variant="ghost" size="sm" class="relative h-8 w-8 p-0">
                <Bell class="h-4 w-4" />
                <span
                    v-if="unreadCount > 0"
                    class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-destructive text-[10px] font-bold text-destructive-foreground"
                >
                    {{ unreadCount > 9 ? '9+' : unreadCount }}
                </span>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80 p-0" align="end">
            <div class="flex items-center justify-between border-b px-3 py-2">
                <h4 class="text-sm font-semibold">Notifications</h4>
                <Button
                    v-if="notifications.some((n) => !n.read_at)"
                    variant="ghost"
                    size="sm"
                    class="h-7 text-xs"
                    @click="markAllAsRead"
                >
                    <CheckCheck class="mr-1 h-3 w-3" />
                    Mark all read
                </Button>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <div v-if="loading" class="p-4 text-center text-sm text-muted-foreground">
                    Loading...
                </div>
                <div v-else-if="notifications.length === 0" class="p-4 text-center text-sm text-muted-foreground">
                    No notifications yet.
                </div>
                <div v-else>
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="flex items-start gap-3 border-b px-3 py-2.5 last:border-0"
                        :class="!notification.read_at ? 'bg-primary/5' : ''"
                    >
                        <div class="flex-1">
                            <Link
                                v-if="notification.data.task_id && notification.data.project_id"
                                :href="`/projects/${notification.data.project_id}/tasks/${notification.data.task_id}`"
                                class="text-sm hover:underline"
                                @click="open = false"
                            >
                                {{ notification.data.message }}
                            </Link>
                            <p v-else class="text-sm">{{ notification.data.message }}</p>
                            <p class="text-xs text-muted-foreground">{{ timeAgo(notification.created_at) }}</p>
                        </div>
                        <Button
                            v-if="!notification.read_at"
                            variant="ghost"
                            size="sm"
                            class="h-6 w-6 shrink-0 p-0"
                            @click="markAsRead(notification)"
                        >
                            <Check class="h-3 w-3" />
                        </Button>
                    </div>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
