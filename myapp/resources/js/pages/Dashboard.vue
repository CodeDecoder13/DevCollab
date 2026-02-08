<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import MyTasksList from '@/components/dashboard/MyTasksList.vue';
import ProjectSummaryCard from '@/components/dashboard/ProjectSummaryCard.vue';
import RecentActivityCard from '@/components/dashboard/RecentActivityCard.vue';
import TaskStatsCard from '@/components/dashboard/TaskStatsCard.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { ActivityLog, BreadcrumbItem, Project, Task } from '@/types';

defineProps<{
    projects: Project[];
    myTasks: Task[];
    taskStats: { todo: number; in_progress: number; completed: number };
    recentActivity: ActivityLog[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <Button as-child>
                    <Link href="/projects/create">
                        <Plus class="mr-2 h-4 w-4" />
                        New Project
                    </Link>
                </Button>
            </div>

            <TaskStatsCard :stats="taskStats" />

            <div class="grid gap-4 lg:grid-cols-2">
                <MyTasksList :tasks="myTasks" />
                <RecentActivityCard :activities="recentActivity" />
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Active Projects</h2>
                    <Button variant="outline" size="sm" as-child>
                        <Link href="/projects">View All</Link>
                    </Button>
                </div>
                <div
                    v-if="projects.length > 0"
                    class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <ProjectSummaryCard
                        v-for="project in projects"
                        :key="project.id"
                        :project="project"
                    />
                </div>
                <div
                    v-else
                    class="flex flex-col items-center justify-center rounded-lg border border-dashed py-8"
                >
                    <p class="text-muted-foreground">No active projects yet.</p>
                    <Button variant="outline" class="mt-3" as-child>
                        <Link href="/projects/create"
                            >Create your first project</Link
                        >
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
