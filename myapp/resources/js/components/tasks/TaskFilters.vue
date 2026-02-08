<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
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
    members: User[];
    filters: { priority?: string; assignee_id?: string };
}>();

const priority = ref(props.filters.priority ?? 'all');
const assigneeId = ref(props.filters.assignee_id ?? 'all');

function applyFilters() {
    router.get(
        `/projects/${props.projectId}/tasks`,
        {
            priority: priority.value !== 'all' ? priority.value : undefined,
            assignee_id:
                assigneeId.value !== 'all' ? assigneeId.value : undefined,
        },
        { preserveState: true, replace: true },
    );
}
</script>

<template>
    <div class="flex items-center gap-2">
        <Select v-model="priority" @update:model-value="applyFilters">
            <SelectTrigger class="w-36">
                <SelectValue placeholder="Priority" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="all">All Priorities</SelectItem>
                <SelectItem value="critical">Critical</SelectItem>
                <SelectItem value="high">High</SelectItem>
                <SelectItem value="medium">Medium</SelectItem>
                <SelectItem value="low">Low</SelectItem>
            </SelectContent>
        </Select>
        <Select v-model="assigneeId" @update:model-value="applyFilters">
            <SelectTrigger class="w-40">
                <SelectValue placeholder="Assignee" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="all">All Assignees</SelectItem>
                <SelectItem
                    v-for="member in members"
                    :key="member.id"
                    :value="String(member.id)"
                >
                    {{ member.name }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
