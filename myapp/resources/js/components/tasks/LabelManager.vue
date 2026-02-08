<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Plus, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import type { Label } from '@/types';

const props = defineProps<{
    taskLabels: Label[];
    projectLabels: Label[];
    projectId: number;
    taskId: number;
}>();

const showCreate = ref(false);
const newLabelName = ref('');
const newLabelColor = ref('#3B82F6');

const colors = ['#EF4444', '#F97316', '#EAB308', '#22C55E', '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280'];

function toggleLabel(label: Label) {
    router.post(
        `/projects/${props.projectId}/tasks/${props.taskId}/labels/${label.id}/toggle`,
        {},
        { preserveState: true, preserveScroll: true },
    );
}

function createLabel() {
    if (!newLabelName.value.trim()) return;
    router.post(
        `/projects/${props.projectId}/labels`,
        { name: newLabelName.value, color: newLabelColor.value },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                newLabelName.value = '';
                showCreate.value = false;
            },
        },
    );
}

function isActive(label: Label): boolean {
    return props.taskLabels.some((l) => l.id === label.id);
}
</script>

<template>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-muted-foreground">Labels</p>
            <Popover>
                <PopoverTrigger as-child>
                    <Button variant="ghost" size="sm" class="h-6 w-6 p-0">
                        <Plus class="h-4 w-4" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-56 p-2">
                    <div class="space-y-1">
                        <button
                            v-for="label in projectLabels"
                            :key="label.id"
                            class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm hover:bg-accent"
                            @click="toggleLabel(label)"
                        >
                            <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: label.color }" />
                            <span class="flex-1 text-left">{{ label.name }}</span>
                            <span v-if="isActive(label)" class="text-primary">&#10003;</span>
                        </button>
                    </div>
                    <div class="mt-2 border-t pt-2">
                        <div v-if="showCreate" class="space-y-2">
                            <Input
                                v-model="newLabelName"
                                placeholder="Label name"
                                class="h-7 text-sm"
                            />
                            <div class="flex flex-wrap gap-1">
                                <button
                                    v-for="color in colors"
                                    :key="color"
                                    class="h-5 w-5 rounded-full border-2 transition-transform hover:scale-110"
                                    :class="newLabelColor === color ? 'border-foreground' : 'border-transparent'"
                                    :style="{ backgroundColor: color }"
                                    @click="newLabelColor = color"
                                />
                            </div>
                            <div class="flex gap-1">
                                <Button size="sm" class="h-7 flex-1 text-xs" @click="createLabel">Create</Button>
                                <Button variant="ghost" size="sm" class="h-7 text-xs" @click="showCreate = false">Cancel</Button>
                            </div>
                        </div>
                        <Button
                            v-else
                            variant="ghost"
                            size="sm"
                            class="w-full text-xs"
                            @click="showCreate = true"
                        >
                            <Plus class="mr-1 h-3 w-3" />
                            Create label
                        </Button>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
        <div v-if="taskLabels.length > 0" class="flex flex-wrap gap-1">
            <Badge
                v-for="label in taskLabels"
                :key="label.id"
                variant="secondary"
                class="gap-1 text-xs"
                :style="{ backgroundColor: label.color + '20', color: label.color, borderColor: label.color + '40' }"
            >
                {{ label.name }}
            </Badge>
        </div>
        <p v-else class="text-xs text-muted-foreground">No labels</p>
    </div>
</template>
