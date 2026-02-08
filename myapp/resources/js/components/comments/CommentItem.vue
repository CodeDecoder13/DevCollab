<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { Edit, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import type { Comment } from '@/types';

const props = defineProps<{
    comment: Comment;
}>();

const page = usePage();
const isEditing = ref(false);
const editBody = ref(props.comment.body);
const isCurrentUser = page.props.auth.user.id === props.comment.user_id;

function saveEdit() {
    router.put(
        `/comments/${props.comment.id}`,
        { body: editBody.value },
        {
            onSuccess: () => {
                isEditing.value = false;
            },
            preserveScroll: true,
        },
    );
}

function cancelEdit() {
    editBody.value = props.comment.body;
    isEditing.value = false;
}

function deleteComment() {
    router.delete(`/comments/${props.comment.id}`, { preserveScroll: true });
}
</script>

<template>
    <div class="flex gap-3">
        <Avatar class="h-8 w-8">
            <AvatarImage
                v-if="comment.user?.avatar"
                :src="comment.user.avatar"
            />
            <AvatarFallback class="text-xs">{{
                getInitials(comment.user?.name)
            }}</AvatarFallback>
        </Avatar>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium">{{
                    comment.user?.name
                }}</span>
                <span class="text-xs text-muted-foreground">
                    {{ new Date(comment.created_at).toLocaleDateString() }}
                </span>
                <div v-if="isCurrentUser" class="ml-auto flex gap-1">
                    <Button
                        variant="ghost"
                        size="sm"
                        class="h-6 w-6 p-0"
                        @click="isEditing = !isEditing"
                    >
                        <Edit class="h-3 w-3" />
                    </Button>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="h-6 w-6 p-0 text-destructive"
                        @click="deleteComment"
                    >
                        <Trash2 class="h-3 w-3" />
                    </Button>
                </div>
            </div>
            <div v-if="isEditing" class="mt-2 space-y-2">
                <Textarea v-model="editBody" rows="3" />
                <div class="flex gap-2">
                    <Button size="sm" @click="saveEdit">Save</Button>
                    <Button size="sm" variant="outline" @click="cancelEdit"
                        >Cancel</Button
                    >
                </div>
            </div>
            <p v-else class="mt-1 text-sm whitespace-pre-wrap">
                {{ comment.body }}
            </p>
        </div>
    </div>
</template>
