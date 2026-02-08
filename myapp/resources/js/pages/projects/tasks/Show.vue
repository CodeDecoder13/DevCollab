<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Trash2 } from 'lucide-vue-next';
import { nextTick, ref } from 'vue';
import AttachmentList from '@/components/attachments/AttachmentList.vue';
import AttachmentUpload from '@/components/attachments/AttachmentUpload.vue';
import CommentForm from '@/components/comments/CommentForm.vue';
import CommentList from '@/components/comments/CommentList.vue';
import LabelManager from '@/components/tasks/LabelManager.vue';
import SubtaskChecklist from '@/components/tasks/SubtaskChecklist.vue';
import TaskPriorityBadge from '@/components/tasks/TaskPriorityBadge.vue';
import TaskStatusBadge from '@/components/tasks/TaskStatusBadge.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import { timeAgo } from '@/lib/utils';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Project, Task } from '@/types';

const props = defineProps<{
    project: Project;
    task: Task;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name, href: `/projects/${props.project.id}` },
    { title: 'Tasks', href: `/projects/${props.project.id}/tasks` },
    { title: props.task.title },
];

const deleteDialogOpen = ref(false);

function deleteTask() {
    router.delete(`/projects/${props.project.id}/tasks/${props.task.id}`, {
        onFinish: () => {
            deleteDialogOpen.value = false;
        },
    });
}

const editingTitle = ref(false);
const editTitle = ref(props.task.title);
const titleInput = ref<HTMLInputElement>();

function startEditTitle() {
    editTitle.value = props.task.title;
    editingTitle.value = true;
    nextTick(() => titleInput.value?.focus());
}

function saveTitle() {
    if (editTitle.value.trim() && editTitle.value !== props.task.title) {
        router.patch(
            `/projects/${props.project.id}/tasks/${props.task.id}`,
            { title: editTitle.value },
            { preserveState: true, preserveScroll: true },
        );
    }
    editingTitle.value = false;
}

function cancelEditTitle() {
    editingTitle.value = false;
    editTitle.value = props.task.title;
}

const editingDesc = ref(false);
const editDesc = ref(props.task.description ?? '');
const descInput = ref<HTMLTextAreaElement>();

function startEditDesc() {
    editDesc.value = props.task.description ?? '';
    editingDesc.value = true;
    nextTick(() => descInput.value?.focus());
}

function saveDesc() {
    if (editDesc.value !== (props.task.description ?? '')) {
        router.patch(
            `/projects/${props.project.id}/tasks/${props.task.id}`,
            { description: editDesc.value || null },
            { preserveState: true, preserveScroll: true },
        );
    }
    editingDesc.value = false;
}

function cancelEditDesc() {
    editingDesc.value = false;
    editDesc.value = props.task.description ?? '';
}
</script>

<template>
    <Head :title="task.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <div v-if="editingTitle">
                        <Input
                            ref="titleInput"
                            v-model="editTitle"
                            class="text-2xl font-bold"
                            @keydown.enter="saveTitle"
                            @keydown.escape="cancelEditTitle"
                            @blur="saveTitle"
                        />
                    </div>
                    <h1
                        v-else
                        class="cursor-pointer text-2xl font-bold hover:text-primary/80"
                        @click="startEditTitle"
                    >
                        {{ task.title }}
                    </h1>
                    <div class="mt-2 flex items-center gap-2">
                        <TaskStatusBadge :status="task.status" />
                        <TaskPriorityBadge :priority="task.priority" />
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link
                            :href="`/projects/${project.id}/tasks/${task.id}/edit`"
                        >
                            <Edit class="mr-1 h-4 w-4" />
                            Edit
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

            <div class="grid gap-4 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Description</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="editingDesc">
                                <Textarea
                                    ref="descInput"
                                    v-model="editDesc"
                                    rows="4"
                                    @keydown.escape="cancelEditDesc"
                                />
                                <div class="mt-2 flex gap-2">
                                    <Button size="sm" @click="saveDesc">Save</Button>
                                    <Button variant="ghost" size="sm" @click="cancelEditDesc">Cancel</Button>
                                </div>
                            </div>
                            <div
                                v-else
                                class="cursor-pointer rounded p-1 hover:bg-accent/50"
                                @click="startEditDesc"
                            >
                                <p
                                    v-if="task.description"
                                    class="text-sm whitespace-pre-wrap"
                                >
                                    {{ task.description }}
                                </p>
                                <p v-else class="text-sm text-muted-foreground">
                                    Click to add a description...
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Subtasks</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <SubtaskChecklist
                                :subtasks="task.subtasks ?? []"
                                :project-id="project.id"
                                :parent-task-id="task.id"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Comments</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <CommentList :comments="task.comments ?? []" />
                            <Separator />
                            <CommentForm
                                :project-id="project.id"
                                :task-id="task.id"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Attachments</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <AttachmentList
                                :attachments="task.attachments ?? []"
                            />
                            <AttachmentUpload
                                :project-id="project.id"
                                :task-id="task.id"
                            />
                        </CardContent>
                    </Card>
                </div>

                <div class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Details</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-sm">
                            <div>
                                <p class="text-muted-foreground">Assignee</p>
                                <div
                                    v-if="task.assignee"
                                    class="mt-1 flex items-center gap-2"
                                >
                                    <Avatar class="h-6 w-6">
                                        <AvatarImage
                                            v-if="task.assignee.avatar"
                                            :src="task.assignee.avatar"
                                        />
                                        <AvatarFallback class="text-[10px]">{{
                                            getInitials(task.assignee.name)
                                        }}</AvatarFallback>
                                    </Avatar>
                                    <span>{{ task.assignee.name }}</span>
                                </div>
                                <span v-else class="text-muted-foreground"
                                    >Unassigned</span
                                >
                            </div>
                            <Separator />
                            <div>
                                <p class="text-muted-foreground">Creator</p>
                                <div
                                    v-if="task.creator"
                                    class="mt-1 flex items-center gap-2"
                                >
                                    <Avatar class="h-6 w-6">
                                        <AvatarImage
                                            v-if="task.creator.avatar"
                                            :src="task.creator.avatar"
                                        />
                                        <AvatarFallback class="text-[10px]">{{
                                            getInitials(task.creator.name)
                                        }}</AvatarFallback>
                                    </Avatar>
                                    <span>{{ task.creator.name }}</span>
                                </div>
                            </div>
                            <Separator />
                            <div>
                                <p class="text-muted-foreground">Due Date</p>
                                <p v-if="task.due_date" class="mt-1">
                                    {{ new Date(task.due_date).toLocaleDateString() }}
                                    <span class="text-xs text-muted-foreground">({{ timeAgo(task.due_date) }})</span>
                                </p>
                                <p v-else class="mt-1 text-muted-foreground">
                                    No due date
                                </p>
                            </div>
                            <Separator />
                            <div>
                                <p class="text-muted-foreground">Created</p>
                                <p class="mt-1">
                                    {{ timeAgo(task.created_at) }}
                                </p>
                            </div>
                            <Separator />
                            <LabelManager
                                :task-labels="task.labels ?? []"
                                :project-labels="project.labels ?? []"
                                :project-id="project.id"
                                :task-id="task.id"
                            />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Task</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ task.title }}"? This
                        will also delete all comments and attachments.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false"
                        >Cancel</Button
                    >
                    <Button variant="destructive" @click="deleteTask"
                        >Delete</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
