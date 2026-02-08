<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Trash2, UserPlus } from 'lucide-vue-next';
import { ref } from 'vue';
import AddMemberDialog from '@/components/projects/AddMemberDialog.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Project, ProjectMember, User } from '@/types';

const props = defineProps<{
    project: Project;
    availableUsers: User[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Projects', href: '/projects' },
    { title: props.project.name, href: `/projects/${props.project.id}` },
    { title: 'Members' },
];

const addDialogOpen = ref(false);
const removeDialogOpen = ref(false);
const memberToRemove = ref<ProjectMember | null>(null);

function updateRole(member: ProjectMember, role: string) {
    router.put(`/projects/${props.project.id}/members/${member.id}`, { role });
}

function confirmRemove(member: ProjectMember) {
    memberToRemove.value = member;
    removeDialogOpen.value = true;
}

function removeMember() {
    if (!memberToRemove.value) return;
    router.delete(
        `/projects/${props.project.id}/members/${memberToRemove.value.id}`,
        {
            onFinish: () => {
                removeDialogOpen.value = false;
                memberToRemove.value = null;
            },
        },
    );
}
</script>

<template>
    <Head :title="`${project.name} - Members`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Members</h1>
                <Button @click="addDialogOpen = true">
                    <UserPlus class="mr-2 h-4 w-4" />
                    Add Member
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>User</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="member in project.members"
                            :key="member.id"
                        >
                            <TableCell>
                                <div class="flex items-center gap-3">
                                    <Avatar class="h-8 w-8">
                                        <AvatarImage
                                            v-if="member.avatar"
                                            :src="member.avatar"
                                        />
                                        <AvatarFallback>{{
                                            getInitials(member.name)
                                        }}</AvatarFallback>
                                    </Avatar>
                                    <div>
                                        <p class="font-medium">
                                            {{ member.name }}
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ member.email }}
                                        </p>
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>
                                <template v-if="member.pivot.role === 'owner'">
                                    <Badge>Owner</Badge>
                                </template>
                                <Select
                                    v-else
                                    :model-value="member.pivot.role"
                                    @update:model-value="
                                        (val: string) => updateRole(member, val)
                                    "
                                >
                                    <SelectTrigger class="w-32">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="manager"
                                            >Manager</SelectItem
                                        >
                                        <SelectItem value="member"
                                            >Member</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button
                                    v-if="member.pivot.role !== 'owner'"
                                    variant="ghost"
                                    size="sm"
                                    class="text-destructive"
                                    @click="confirmRemove(member)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>

        <AddMemberDialog
            v-model:open="addDialogOpen"
            :project-id="project.id"
            :available-users="availableUsers"
        />

        <Dialog v-model:open="removeDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Remove Member</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to remove
                        {{ memberToRemove?.name }} from this project?
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="removeDialogOpen = false"
                        >Cancel</Button
                    >
                    <Button variant="destructive" @click="removeMember"
                        >Remove</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
