<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ChevronDown, ChevronRight, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import draggable from 'vuedraggable';
import TaskCard from '@/components/tasks/TaskCard.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { Task, TaskStatus } from '@/types';

const props = defineProps<{
    title: string;
    tasks: Task[];
    projectId: number;
    status: TaskStatus;
}>();

defineEmits<{
    change: [
        event: {
            added?: { element: Task };
            removed?: { element: Task };
            moved?: { element: Task };
        },
    ];
}>();

const statusColors: Record<string, string> = {
    todo: 'bg-slate-50 dark:bg-slate-900/30',
    in_progress: 'bg-amber-50/50 dark:bg-amber-900/10',
    completed: 'bg-emerald-50/50 dark:bg-emerald-900/10',
};

const statusBadgeColors: Record<string, string> = {
    todo: 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
    in_progress: 'bg-amber-200 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
    completed: 'bg-emerald-200 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
};

const collapsed = ref(false);
const showQuickAdd = ref(false);
const quickAddTitle = ref('');

function submitQuickAdd() {
    if (!quickAddTitle.value.trim()) return;
    router.post(
        `/projects/${props.projectId}/tasks/quick`,
        { title: quickAddTitle.value, status: props.status },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                quickAddTitle.value = '';
                showQuickAdd.value = false;
            },
        },
    );
}
</script>

<template>
    <div
        class="flex min-h-[200px] w-full flex-col rounded-lg p-2 md:w-80"
        :class="statusColors[status] ?? 'bg-muted/50'"
    >
        <div class="mb-2 flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <Button variant="ghost" size="sm" class="h-6 w-6 p-0" @click="collapsed = !collapsed">
                    <ChevronDown v-if="!collapsed" class="h-4 w-4" />
                    <ChevronRight v-else class="h-4 w-4" />
                </Button>
                <h3 class="text-sm font-semibold">{{ title }}</h3>
                <span
                    class="rounded-full px-2 py-0.5 text-xs font-medium"
                    :class="statusBadgeColors[status] ?? 'bg-muted'"
                >{{ tasks.length }}</span>
            </div>
            <Button variant="ghost" size="sm" class="h-6 w-6 p-0" @click="showQuickAdd = !showQuickAdd">
                <Plus class="h-4 w-4" />
            </Button>
        </div>

        <div v-if="showQuickAdd" class="mb-2 px-1">
            <form @submit.prevent="submitQuickAdd" class="flex gap-1">
                <Input
                    v-model="quickAddTitle"
                    placeholder="Task title..."
                    class="h-8 text-sm"
                    autofocus
                    @keydown.escape="showQuickAdd = false"
                />
                <Button type="submit" size="sm" class="h-8 px-2">Add</Button>
            </form>
        </div>

        <draggable
            v-show="!collapsed"
            :list="tasks"
            group="tasks"
            item-key="id"
            class="flex flex-1 flex-col gap-2"
            ghost-class="task-ghost"
            chosen-class="task-chosen"
            @change="$emit('change', $event)"
        >
            <template #item="{ element }">
                <TaskCard :task="element" :project-id="projectId" />
            </template>
        </draggable>
    </div>
</template>

<style scoped>
:deep(.task-ghost) {
    opacity: 0.5;
    transform: rotate(2deg);
}
:deep(.task-chosen) {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: scale(1.02);
}
</style>
