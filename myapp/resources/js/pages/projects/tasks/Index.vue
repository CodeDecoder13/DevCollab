<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { KanbanSquare, List, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import TaskBoard from '@/components/tasks/TaskBoard.vue';
import TaskFilters from '@/components/tasks/TaskFilters.vue';
import TaskList from '@/components/tasks/TaskList.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Project, Task } from '@/types';

const props = defineProps<{
    project: Project;
    tasks: Task[];
    filters: { priority?: string; assignee_id?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name, href: `/projects/${props.project.id}` },
    { title: 'Tasks' },
];

const viewMode = ref<'board' | 'list'>('board');
</script>

<template>
    <Head :title="`${project.name} - Tasks`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Tasks</h1>
                <div class="flex items-center gap-2">
                    <TaskFilters
                        :project-id="project.id"
                        :members="project.members ?? []"
                        :filters="filters"
                    />
                    <div class="flex rounded-md border">
                        <Button
                            variant="ghost"
                            size="sm"
                            :class="{ 'bg-accent': viewMode === 'board' }"
                            @click="viewMode = 'board'"
                        >
                            <KanbanSquare class="h-4 w-4" />
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            :class="{ 'bg-accent': viewMode === 'list' }"
                            @click="viewMode = 'list'"
                        >
                            <List class="h-4 w-4" />
                        </Button>
                    </div>
                    <Button as-child>
                        <Link :href="`/projects/${project.id}/tasks/create`">
                            <Plus class="mr-2 h-4 w-4" />
                            New Task
                        </Link>
                    </Button>
                </div>
            </div>

            <TaskBoard
                v-if="viewMode === 'board'"
                :tasks="tasks"
                :project-id="project.id"
            />
            <TaskList v-else :tasks="tasks" :project-id="project.id" />
        </div>
    </AppLayout>
</template>
