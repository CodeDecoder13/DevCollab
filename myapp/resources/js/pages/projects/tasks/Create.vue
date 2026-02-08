<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AssigneeCombobox from '@/components/AssigneeCombobox.vue';
import FormField from '@/components/FormField.vue';
import { Button } from '@/components/ui/button';
import { DatePicker } from '@/components/ui/date-picker';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Project } from '@/types';

const props = defineProps<{
    project: Project;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name, href: `/projects/${props.project.id}` },
    { title: 'Tasks', href: `/projects/${props.project.id}/tasks` },
    { title: 'Create' },
];

const form = useForm({
    title: '',
    description: '',
    priority: 'medium',
    assignee_id: 'none' as string,
    due_date: '',
});

function submit() {
    form.post(`/projects/${props.project.id}/tasks`, {
        data: {
            ...form.data(),
            assignee_id: form.assignee_id === 'none' ? null : form.assignee_id,
            due_date: form.due_date || null,
        },
    });
}
</script>

<template>
    <Head title="Create Task" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Create Task</h1>

            <form @submit.prevent="submit" class="max-w-lg space-y-4">
                <FormField label="Title" html-for="title" :error="form.errors.title">
                    <Input
                        id="title"
                        v-model="form.title"
                        placeholder="Task title"
                    />
                </FormField>

                <FormField label="Description" html-for="description" :error="form.errors.description">
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        placeholder="Describe the task..."
                    />
                </FormField>

                <FormField label="Priority">
                    <Select v-model="form.priority">
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="low">Low</SelectItem>
                            <SelectItem value="medium">Medium</SelectItem>
                            <SelectItem value="high">High</SelectItem>
                            <SelectItem value="critical">Critical</SelectItem>
                        </SelectContent>
                    </Select>
                </FormField>

                <FormField label="Assignee">
                    <AssigneeCombobox
                        v-model="form.assignee_id"
                        :members="project.members ?? []"
                    />
                </FormField>

                <FormField label="Due Date">
                    <DatePicker v-model="form.due_date" />
                </FormField>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing"
                        >Create Task</Button
                    >
                    <Button
                        type="button"
                        variant="outline"
                        @click="$inertia.visit(`/projects/${project.id}/tasks`)"
                        >Cancel</Button
                    >
                </div>
            </form>
        </div>
    </AppLayout>
</template>
