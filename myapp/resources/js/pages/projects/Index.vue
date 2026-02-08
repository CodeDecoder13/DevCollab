<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FolderKanban, Plus } from 'lucide-vue-next';
import EmptyState from '@/components/EmptyState.vue';
import ProjectCard from '@/components/projects/ProjectCard.vue';
import { Button } from '@/components/ui/button';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Project } from '@/types';

type PaginatedProjects = {
    data: Project[];
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
};

defineProps<{
    projects: PaginatedProjects;
    filters: { status?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
];
</script>

<template>
    <Head title="Projects" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Projects</h1>
                <Button as-child>
                    <Link href="/projects/create">
                        <Plus class="mr-2 h-4 w-4" />
                        New Project
                    </Link>
                </Button>
            </div>

            <Tabs :default-value="filters.status ?? 'all'">
                <TabsList>
                    <TabsTrigger value="all" as-child>
                        <Link href="/projects" preserve-state>All</Link>
                    </TabsTrigger>
                    <TabsTrigger value="active" as-child>
                        <Link href="/projects?status=active" preserve-state
                            >Active</Link
                        >
                    </TabsTrigger>
                    <TabsTrigger value="on_hold" as-child>
                        <Link href="/projects?status=on_hold" preserve-state
                            >On Hold</Link
                        >
                    </TabsTrigger>
                    <TabsTrigger value="completed" as-child>
                        <Link href="/projects?status=completed" preserve-state
                            >Completed</Link
                        >
                    </TabsTrigger>
                </TabsList>
            </Tabs>

            <div
                v-if="projects.data.length > 0"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <ProjectCard
                    v-for="project in projects.data"
                    :key="project.id"
                    :project="project"
                />
            </div>

            <EmptyState
                v-else
                :icon="FolderKanban"
                title="No projects found"
                description="Get started by creating your first project."
            >
                <Button variant="outline" as-child>
                    <Link href="/projects/create">Create your first project</Link>
                </Button>
            </EmptyState>

            <div
                v-if="projects.last_page > 1"
                class="flex items-center justify-center gap-1"
            >
                <template v-for="link in projects.links" :key="link.label">
                    <Button
                        v-if="link.url"
                        variant="outline"
                        size="sm"
                        :class="{
                            'bg-primary text-primary-foreground': link.active,
                        }"
                        as-child
                    >
                        <Link :href="link.url"
                            ><span v-html="link.label"
                        /></Link>
                    </Button>
                    <Button v-else variant="outline" size="sm" disabled>
                        <span v-html="link.label" />
                    </Button>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
