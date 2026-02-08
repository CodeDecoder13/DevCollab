<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, User } from '@/types';

type PaginatedUsers = {
    data: User[];
    links: { url: string | null; label: string; active: boolean }[];
    current_page: number;
    last_page: number;
};

const props = defineProps<{
    users: PaginatedUsers;
    filters: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin' },
    { title: 'User Management', href: '/admin/users' },
];

const search = ref(props.filters.search ?? '');
const deleteDialogOpen = ref(false);
const userToDelete = ref<User | null>(null);

function handleSearch() {
    router.get(
        '/admin/users',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

function confirmDelete(user: User) {
    userToDelete.value = user;
    deleteDialogOpen.value = true;
}

function deleteUser() {
    if (!userToDelete.value) return;
    router.delete(`/admin/users/${userToDelete.value.id}`, {
        onFinish: () => {
            deleteDialogOpen.value = false;
            userToDelete.value = null;
        },
    });
}
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">User Management</h1>
                <Button as-child>
                    <Link href="/admin/users/create">
                        <Plus class="mr-2 h-4 w-4" />
                        Create User
                    </Link>
                </Button>
            </div>

            <div class="flex items-center gap-2">
                <Input
                    v-model="search"
                    placeholder="Search users..."
                    class="max-w-sm"
                    @keyup.enter="handleSearch"
                />
                <Button variant="secondary" @click="handleSearch">
                    Search
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Joined</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">
                                {{ user.name }}
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        user.role === 'admin'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{ user.role }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                {{
                                    new Date(
                                        user.created_at,
                                    ).toLocaleDateString()
                                }}
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link
                                            :href="`/admin/users/${user.id}/edit`"
                                        >
                                            <Pencil class="mr-1 h-4 w-4" />
                                            Edit
                                        </Link>
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="text-destructive"
                                        @click="confirmDelete(user)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="users.data.length === 0">
                            <TableCell
                                colspan="5"
                                class="py-8 text-center text-muted-foreground"
                            >
                                No users found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div
                v-if="users.last_page > 1"
                class="flex items-center justify-center gap-1"
            >
                <template v-for="link in users.links" :key="link.label">
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

        <Dialog v-model:open="deleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete User</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete
                        "{{ userToDelete?.name }}"? This will remove all their
                        data. This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="deleteDialogOpen = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteUser">
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
