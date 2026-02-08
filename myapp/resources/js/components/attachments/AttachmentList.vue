<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Download, FileIcon, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import type { Attachment } from '@/types';

defineProps<{
    attachments: Attachment[];
}>();

const page = usePage();

function formatSize(bytes: number): string {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function deleteAttachment(attachment: Attachment) {
    router.delete(`/attachments/${attachment.id}`, { preserveScroll: true });
}
</script>

<template>
    <div v-if="attachments.length > 0" class="space-y-2">
        <div
            v-for="attachment in attachments"
            :key="attachment.id"
            class="flex items-center justify-between rounded-md border p-2"
        >
            <div class="flex items-center gap-2">
                <FileIcon class="h-4 w-4 text-muted-foreground" />
                <div>
                    <p class="text-sm font-medium">{{ attachment.filename }}</p>
                    <p class="text-xs text-muted-foreground">
                        {{ formatSize(attachment.size) }} &middot;
                        {{ attachment.user?.name }} &middot;
                        {{
                            new Date(attachment.created_at).toLocaleDateString()
                        }}
                    </p>
                </div>
            </div>
            <div class="flex gap-1">
                <Button variant="ghost" size="sm" as-child>
                    <a :href="`/attachments/${attachment.id}`" target="_blank">
                        <Download class="h-4 w-4" />
                    </a>
                </Button>
                <Button
                    v-if="
                        attachment.user_id === page.props.auth.user.id ||
                        page.props.auth.user.role === 'admin'
                    "
                    variant="ghost"
                    size="sm"
                    class="text-destructive"
                    @click="deleteAttachment(attachment)"
                >
                    <Trash2 class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
    <p v-else class="text-sm text-muted-foreground">No attachments yet.</p>
</template>
