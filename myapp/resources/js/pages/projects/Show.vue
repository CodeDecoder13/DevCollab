<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Archive, Edit, Settings, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import ProjectStatusBadge from '@/components/projects/ProjectStatusBadge.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import type { ActivityLog, BreadcrumbItem, Project } from '@/types';

const props = defineProps<{
    project: Project;
    recentActivity: ActivityLog[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name },
];

const deleteDialogOpen = ref(false);

function deleteProject() {
    router.delete(`/projects/${props.project.id}`, {
        onFinish: () => {
            deleteDialogOpen.value = false;
        },
    });
}

function toggleArchive() {
    router.patch(`/projects/${props.project.id}/archive`);
}

const tasksByStatus = {
    todo: props.project.tasks?.filter((t) => t.status === 'todo').length ?? 0,
    in_progress:
        props.project.tasks?.filter((t) => t.status === 'in_progress').length ??
        0,
    completed:
        props.project.tasks?.filter((t) => t.status === 'completed').length ??
        0,
};
</script>

<template>
    <Head :title="project.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold">{{ project.name }}</h1>
                        <ProjectStatusBadge :status="project.status" />
                        <Badge v-if="project.archived_at" variant="outline"
                            >Archived</Badge
                        >
                    </div>
                    <p
                        v-if="project.description"
                        class="mt-1 text-muted-foreground"
                    >
                        {{ project.description }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="`/projects/${project.id}/tasks`">
                            View Tasks
                        </Link>
                    </Button>
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="`/projects/${project.id}/edit`">
                            <Edit class="mr-1 h-4 w-4" />
                            Edit
                        </Link>
                    </Button>
                    <Button variant="outline" size="sm" @click="toggleArchive">
                        <Archive class="mr-1 h-4 w-4" />
                        {{ project.archived_at ? 'Unarchive' : 'Archive' }}
                    </Button>
                    <Button variant="outline" size="sm" as-child>
                        <Link :href="`/projects/${project.id}/members`">
                            <Settings class="mr-1 h-4 w-4" />
                            Members
                        </Link>
                    </Button>
                    <Button
                        variant="destructive"
                        size="sm"
                        @click="deleteDialogOpen = true"
                    >
                        <Trash2 class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                            >To Do</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ tasksByStatus.todo }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                            >In Progress</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ tasksByStatus.in_progress }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                            >Completed</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold">
                            {{ tasksByStatus.completed }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Members</CardTitle>
                            <Button variant="outline" size="sm" as-child>
                                <Link :href="`/projects/${project.id}/members`"
                                    >Manage</Link
                                >
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="project.members && project.members.length > 0"
                            class="space-y-2"
                        >
                            <div
                                v-for="member in project.members"
                                :key="member.id"
                                class="flex items-center justify-between text-sm"
                            >
                                <span>{{ member.name }}</span>
                                <Badge variant="outline">{{
                                    member.pivot.role
                                }}</Badge>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">
                            No members yet.
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Recent Activity</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="recentActivity.length > 0" class="space-y-3">
                            <div
                                v-for="activity in recentActivity"
                                :key="activity.id"
                                class="flex items-start gap-2 text-sm"
                            >
                                <div
                                    class="mt-1 h-2 w-2 rounded-full bg-primary"
                                />
                                <div>
                                    <p>{{ activity.description }}</p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ activity.user?.name }} &middot;
                                        {{
                                            new Date(
                                                activity.created_at,
                                            ).toLocaleDateString()
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">
                            No activity yet.
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>

        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Project</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ project.name }}"?
                        This will delete all tasks, comments, and attachments.
                        This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false"
                        >Cancel</Button
                    >
                    <Button variant="destructive" @click="deleteProject"
                        >Delete</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
