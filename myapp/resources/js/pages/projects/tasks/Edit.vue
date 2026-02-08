<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
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
    {
        title: props.task.title,
        href: `/projects/${props.project.id}/tasks/${props.task.id}`,
    },
    { title: 'Edit' },
];

const form = useForm({
    title: props.task.title,
    description: props.task.description ?? '',
    status: props.task.status,
    priority: props.task.priority,
    assignee_id: props.task.assignee_id ? String(props.task.assignee_id) : 'none',
    due_date: props.task.due_date ?? '',
});

function submit() {
    form.put(`/projects/${props.project.id}/tasks/${props.task.id}`, {
        data: {
            ...form.data(),
            assignee_id: form.assignee_id === 'none' ? null : form.assignee_id,
            due_date: form.due_date || null,
        },
    });
}
</script>

<template>
    <Head title="Edit Task" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Edit Task</h1>

            <form @submit.prevent="submit" class="max-w-lg space-y-4">
                <div class="space-y-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" />
                    <p
                        v-if="form.errors.title"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.title }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                    />
                    <p
                        v-if="form.errors.description"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.description }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label>Status</Label>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="todo">To Do</SelectItem>
                                <SelectItem value="in_progress"
                                    >In Progress</SelectItem
                                >
                                <SelectItem value="completed"
                                    >Completed</SelectItem
                                >
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="space-y-2">
                        <Label>Priority</Label>
                        <Select v-model="form.priority">
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="low">Low</SelectItem>
                                <SelectItem value="medium">Medium</SelectItem>
                                <SelectItem value="high">High</SelectItem>
                                <SelectItem value="critical"
                                    >Critical</SelectItem
                                >
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label>Assignee</Label>
                    <Select v-model="form.assignee_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Unassigned" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="none">Unassigned</SelectItem>
                            <SelectItem
                                v-for="member in project.members"
                                :key="member.id"
                                :value="String(member.id)"
                            >
                                {{ member.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <Label for="due_date">Due Date</Label>
                    <Input id="due_date" v-model="form.due_date" type="date" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing"
                        >Save Changes</Button
                    >
                    <Button
                        type="button"
                        variant="outline"
                        @click="
                            $inertia.visit(
                                `/projects/${project.id}/tasks/${task.id}`,
                            )
                        "
                        >Cancel</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
