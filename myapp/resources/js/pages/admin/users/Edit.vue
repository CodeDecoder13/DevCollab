<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
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
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, User } from '@/types';

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin' },
    { title: 'User Management', href: '/admin/users' },
    { title: `Edit: ${props.user.name}` },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    password: '',
});

function submit() {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Edit User</h1>

            <div class="max-w-md rounded-lg border p-6">
                <div
                    v-if="Object.keys(form.errors).length > 0"
                    class="mb-4 rounded-md border border-destructive/50 bg-destructive/10 p-3"
                >
                    <p class="text-sm font-medium text-destructive">
                        Please fix the following errors:
                    </p>
                    <ul class="mt-1 list-inside list-disc text-sm text-destructive">
                        <li v-for="(error, key) in form.errors" :key="key">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            placeholder="Full name"
                        />
                        <p
                            v-if="form.errors.name"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Email address"
                        />
                        <p
                            v-if="form.errors.email"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="role">Role</Label>
                        <Select
                            :model-value="form.role"
                            @update:model-value="(val: any) => (form.role = val)"
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="user">User</SelectItem>
                                <SelectItem value="admin">Admin</SelectItem>
                            </SelectContent>
                        </Select>
                        <p
                            v-if="form.errors.role"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.role }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Leave blank to keep current"
                        />
                        <p class="text-xs text-muted-foreground">
                            Only fill this if you want to change the password.
                        </p>
                        <p
                            v-if="form.errors.password"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <Button
                            :disabled="form.processing"
                            @click="submit"
                        >
                            Update User
                        </Button>
                        <Button
                            variant="outline"
                            @click="router.visit('/admin/users')"
                        >
                            Cancel
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
