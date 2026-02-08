<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import MemberAvatarGroup from '@/components/projects/MemberAvatarGroup.vue';
import ProjectStatusBadge from '@/components/projects/ProjectStatusBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { timeAgo } from '@/lib/utils';
import type { Project } from '@/types';

const props = defineProps<{
    project: Project;
}>();

const progress = computed(() => {
    if (!props.project.tasks_count) return 0;
    return Math.round(
        ((props.project.completed_tasks_count ?? 0) /
            props.project.tasks_count) *
            100,
    );
});

const statusBorderColor: Record<string, string> = {
    active: 'border-l-emerald-500',
    on_hold: 'border-l-amber-500',
    completed: 'border-l-blue-500',
};

const progressColor = computed(() => {
    if (progress.value < 30) return 'bg-red-500';
    if (progress.value < 70) return 'bg-amber-500';
    return 'bg-emerald-500';
});
</script>

<template>
    <Link :href="`/projects/${project.id}`">
        <Card
            class="border-l-4 transition-shadow hover:shadow-md"
            :class="statusBorderColor[project.status] ?? 'border-l-slate-300'"
        >
            <CardHeader class="pb-2">
                <div class="flex items-start justify-between">
                    <CardTitle class="text-sm">{{ project.name }}</CardTitle>
                    <ProjectStatusBadge :status="project.status" />
                </div>
            </CardHeader>
            <CardContent class="space-y-2">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs text-muted-foreground">
                        <span>{{ project.completed_tasks_count ?? 0 }}/{{ project.tasks_count ?? 0 }} tasks done</span>
                        <span>{{ progress }}%</span>
                    </div>
                    <div class="h-1 w-full overflow-hidden rounded-full bg-muted">
                        <div
                            class="h-full rounded-full transition-all"
                            :class="progressColor"
                            :style="{ width: `${progress}%` }"
                        />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <MemberAvatarGroup
                        v-if="project.members"
                        :members="project.members"
                        :max="3"
                    />
                    <span class="text-xs text-muted-foreground">{{ timeAgo(project.updated_at) }}</span>
                </div>
            </CardContent>
        </Card>
    </Link>
</template>
