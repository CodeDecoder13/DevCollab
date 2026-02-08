<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { User } from '@/types';

const props = defineProps<{
    projectId: number;
    availableUsers: User[];
}>();

const open = defineModel<boolean>('open', { default: false });

const search = ref('');
const selectedUserId = ref<number | null>(null);

const form = useForm({
    user_id: 0,
    role: 'member',
});

watch(search, (value) => {
    if (value.length >= 2) {
        router.reload({
            data: { search: value },
            only: ['availableUsers'],
            preserveState: true,
        });
    }
});

function submit() {
    if (!selectedUserId.value) return;
    form.user_id = selectedUserId.value;
    form.post(`/projects/${props.projectId}/members`, {
        onSuccess: () => {
            open.value = false;
            selectedUserId.value = null;
            search.value = '';
            form.reset();
        },
    });
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Add Member</DialogTitle>
                <DialogDescription>
                    Search for a user to add to this project.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label>Search Users</Label>
                    <Input
                        v-model="search"
                        placeholder="Type a name or email..."
                    />
                    <div
                        v-if="availableUsers.length > 0"
                        class="max-h-40 overflow-y-auto rounded-md border"
                    >
                        <button
                            v-for="user in availableUsers"
                            :key="user.id"
                            type="button"
                            class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm hover:bg-accent"
                            :class="{ 'bg-accent': selectedUserId === user.id }"
                            @click="selectedUserId = user.id"
                        >
                            <span class="font-medium">{{ user.name }}</span>
                            <span class="text-muted-foreground">{{
                                user.email
                            }}</span>
                        </button>
                    </div>
                    <p
                        v-else-if="search.length >= 2"
                        class="text-sm text-muted-foreground"
                    >
                        No users found.
                    </p>
                </div>

                <div class="space-y-2">
                    <Label>Role</Label>
                    <Select v-model="form.role">
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="member">Member</SelectItem>
                            <SelectItem value="manager">Manager</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="open = false"
                        >Cancel</Button
                    >
                    <Button
                        type="submit"
                        :disabled="!selectedUserId || form.processing"
                        >Add Member</Button
                    >
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
