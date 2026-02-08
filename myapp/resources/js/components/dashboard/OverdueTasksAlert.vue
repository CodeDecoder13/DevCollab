<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { AlertTriangle, ChevronDown, ChevronUp } from 'lucide-vue-next';
import { ref } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import type { Task } from '@/types';

defineProps<{
    tasks: Task[];
}>();

const expanded = ref(false);
</script>

<template>
    <Alert v-if="tasks.length > 0" variant="destructive">
        <AlertTriangle class="h-4 w-4" />
        <AlertTitle class="flex items-center justify-between">
            <span>Overdue Tasks</span>
            <Button
                variant="ghost"
                size="sm"
                class="h-6 w-6 p-0"
                @click="expanded = !expanded"
            >
                <ChevronUp v-if="expanded" class="h-4 w-4" />
                <ChevronDown v-else class="h-4 w-4" />
            </Button>
        </AlertTitle>
        <AlertDescription>
            <p class="text-sm">You have {{ tasks.length }} overdue task{{ tasks.length > 1 ? 's' : '' }}.</p>
            <div v-if="expanded" class="mt-2 space-y-1">
                <Link
                    v-for="task in tasks"
                    :key="task.id"
                    :href="`/projects/${task.project_id}/tasks/${task.id}`"
                    class="block rounded p-1.5 text-sm hover:bg-destructive/10"
                >
                    <span class="font-medium">{{ task.title }}</span>
                    <span v-if="task.project" class="ml-2 text-xs opacity-70">{{ task.project.name }}</span>
                </Link>
            </div>
        </AlertDescription>
    </Alert>
</template>
