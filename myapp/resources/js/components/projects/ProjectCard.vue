<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import MemberAvatarGroup from '@/components/projects/MemberAvatarGroup.vue';
import ProjectStatusBadge from '@/components/projects/ProjectStatusBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
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
</script>

<template>
    <Link :href="`/projects/${project.id}`">
        <Card class="transition-colors hover:bg-accent/50">
            <CardHeader class="pb-3">
                <div class="flex items-start justify-between">
                    <CardTitle class="text-base">{{ project.name }}</CardTitle>
                    <ProjectStatusBadge :status="project.status" />
                </div>
            </CardHeader>
            <CardContent class="space-y-3">
                <p
                    v-if="project.description"
                    class="line-clamp-2 text-sm text-muted-foreground"
                >
                    {{ project.description }}
                </p>
                <div class="space-y-1">
                    <div
                        class="flex justify-between text-xs text-muted-foreground"
                    >
                        <span
                            >{{ project.completed_tasks_count ?? 0 }}/{{
                                project.tasks_count ?? 0
                            }}
                            tasks</span
                        >
                        <span>{{ progress }}%</span>
                    </div>
                    <Progress :model-value="progress" class="h-1.5" />
                </div>
                <MemberAvatarGroup
                    v-if="project.members"
                    :members="project.members"
                />
            </CardContent>
        </Card>
    </Link>
</template>
