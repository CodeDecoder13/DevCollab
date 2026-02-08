<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FolderKanban } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import OverdueTasksAlert from '@/components/dashboard/OverdueTasksAlert.vue';
import ProjectSummaryCard from '@/components/dashboard/ProjectSummaryCard.vue';
import QuickActionsRow from '@/components/dashboard/QuickActionsRow.vue';
import RecentActivityCard from '@/components/dashboard/RecentActivityCard.vue';
import TaskStatsCard from '@/components/dashboard/TaskStatsCard.vue';
import WelcomeBanner from '@/components/dashboard/WelcomeBanner.vue';
import MyTasksList from '@/components/dashboard/MyTasksList.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { ActivityLog, BreadcrumbItem, Project, Task } from '@/types';

defineProps<{
    projects: Project[];
    myTasks: Task[];
    taskStats: { todo: number; in_progress: number; completed: number };
    recentActivity: ActivityLog[];
    overdueCount: number;
    overdueTasks: Task[];
    dueSoonCount: number;
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
            <WelcomeBanner :overdue-count="overdueCount" />

            <OverdueTasksAlert :tasks="overdueTasks" />

            <QuickActionsRow />

            <TaskStatsCard
                :stats="taskStats"
                :overdue-count="overdueCount"
                :due-soon-count="dueSoonCount"
            />

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
                <EmptyState
                    v-else
                    :icon="FolderKanban"
                    title="No active projects"
                    description="Create your first project to get started."
                >
                    <Button variant="outline" as-child>
                        <Link href="/projects/create">Create your first project</Link>
                    </Button>
                </EmptyState>
            </div>
        </div>
    </AppLayout>
</template>
