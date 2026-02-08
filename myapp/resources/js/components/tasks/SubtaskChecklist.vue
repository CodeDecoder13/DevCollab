<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import type { Task } from '@/types';

const props = defineProps<{
    subtasks: Task[];
    projectId: number;
    parentTaskId: number;
}>();

const showAdd = ref(false);
const newTitle = ref('');

function toggleSubtask(subtask: Task) {
    const newStatus = subtask.status === 'completed' ? 'todo' : 'completed';
    router.patch(
        `/projects/${props.projectId}/tasks/${subtask.id}/status`,
        { status: newStatus },
        { preserveState: true, preserveScroll: true },
    );
}

function addSubtask() {
    if (!newTitle.value.trim()) return;
    router.post(
        `/projects/${props.projectId}/tasks/quick`,
        {
            title: newTitle.value,
            status: 'todo',
            parent_task_id: props.parentTaskId,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                newTitle.value = '';
            },
        },
    );
}

const completedCount = () => props.subtasks.filter((s) => s.status === 'completed').length;
const progress = () => {
    if (props.subtasks.length === 0) return 0;
    return Math.round((completedCount() / props.subtasks.length) * 100);
};
</script>

<template>
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold">Subtasks</h3>
            <span v-if="subtasks.length > 0" class="text-xs text-muted-foreground">
                {{ completedCount() }}/{{ subtasks.length }}
            </span>
        </div>

        <Progress v-if="subtasks.length > 0" :model-value="progress()" class="h-1.5" />

        <div class="space-y-1">
            <div
                v-for="subtask in subtasks"
                :key="subtask.id"
                class="flex items-center gap-2 rounded p-1.5 hover:bg-accent/50"
            >
                <Checkbox
                    :checked="subtask.status === 'completed'"
                    @update:checked="toggleSubtask(subtask)"
                />
                <span
                    class="flex-1 text-sm"
                    :class="subtask.status === 'completed' ? 'text-muted-foreground line-through' : ''"
                >
                    {{ subtask.title }}
                </span>
            </div>
        </div>

        <div v-if="showAdd">
            <form @submit.prevent="addSubtask" class="flex gap-1">
                <Input
                    v-model="newTitle"
                    placeholder="Subtask title..."
                    class="h-8 text-sm"
                    autofocus
                    @keydown.escape="showAdd = false"
                />
                <Button type="submit" size="sm" class="h-8">Add</Button>
            </form>
        </div>
        <Button
            v-else
            variant="ghost"
            size="sm"
            class="w-full"
            @click="showAdd = true"
        >
            <Plus class="mr-1 h-4 w-4" />
            Add subtask
        </Button>
    </div>
</template>
