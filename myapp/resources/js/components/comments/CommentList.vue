<script setup lang="ts">
import CommentItem from '@/components/comments/CommentItem.vue';
import type { Comment } from '@/types';

defineProps<{
    comments: Comment[];
}>();
</script>

<template>
    <div v-if="comments.length > 0" class="space-y-4">
        <CommentItem
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
        />
    </div>
    <p v-else class="text-sm text-muted-foreground">
        No comments yet. Be the first to comment.
    </p>
</template>
