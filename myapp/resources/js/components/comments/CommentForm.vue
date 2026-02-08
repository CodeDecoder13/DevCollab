<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';

const props = defineProps<{
    projectId: number;
    taskId: number;
}>();

const form = useForm({
    body: '',
});

function submit() {
    form.post(`/projects/${props.projectId}/tasks/${props.taskId}/comments`, {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    });
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-2">
        <Textarea
            v-model="form.body"
            placeholder="Write a comment..."
            rows="3"
        />
        <p v-if="form.errors.body" class="text-sm text-destructive">
            {{ form.errors.body }}
        </p>
        <Button
            type="submit"
            size="sm"
            :disabled="form.processing || !form.body.trim()"
        >
            Add Comment
        </Button>
    </form>
</template>
