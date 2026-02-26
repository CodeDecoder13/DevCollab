<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BarChart3,
    CheckSquare,
    FolderKanban,
    GitBranch,
    Layers,
    MessageSquare,
    Sparkles,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Button } from '@/components/ui/button';
import { register } from '@/routes';

const visibleSections = ref<Set<string>>(new Set());
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    visibleSections.value.add(entry.target.getAttribute('data-animate') || '');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 },
    );
    document.querySelectorAll('[data-animate]').forEach((el) => observer?.observe(el));
});

onUnmounted(() => observer?.disconnect());

function isVisible(key: string) {
    return visibleSections.value.has(key);
}

const features = [
    {
        id: 'kanban',
        icon: FolderKanban,
        title: 'Kanban Boards',
        description: 'Visualize your entire workflow with intuitive drag-and-drop kanban boards that adapt to your process.',
        points: ['Drag-and-drop task cards between columns', 'Custom column names and WIP limits', 'Filter and search across boards', 'Color-coded labels and priorities'],
        iconColor: 'text-indigo-600 dark:text-indigo-400',
        bgColor: 'bg-indigo-100 dark:bg-indigo-950/50',
        glowColor: 'from-indigo-500/20 to-blue-500/10',
    },
    {
        id: 'sprints',
        icon: Layers,
        title: 'Sprint Planning',
        description: 'Plan and manage agile sprints with powerful planning tools designed for scrum and kanban teams.',
        points: ['Sprint backlogs with story points', 'Velocity tracking and burn-down charts', 'Sprint retrospective summaries', 'Automated sprint rollover'],
        iconColor: 'text-violet-600 dark:text-violet-400',
        bgColor: 'bg-violet-100 dark:bg-violet-950/50',
        glowColor: 'from-violet-500/20 to-purple-500/10',
    },
    {
        id: 'issues',
        icon: CheckSquare,
        title: 'Issue Tracking',
        description: 'Track bugs, feature requests, and tasks with full context. Never lose sight of what needs to be done.',
        points: ['Custom issue types and workflows', 'Priority levels and due dates', 'Sub-tasks and dependencies', 'Bulk actions and quick filters'],
        iconColor: 'text-emerald-600 dark:text-emerald-400',
        bgColor: 'bg-emerald-100 dark:bg-emerald-950/50',
        glowColor: 'from-emerald-500/20 to-teal-500/10',
    },
    {
        id: 'git',
        icon: GitBranch,
        title: 'Git Integration',
        description: 'Connect your repositories and see code changes linked directly to tasks and issues.',
        points: ['Link commits to tasks automatically', 'Branch and PR status tracking', 'Code review workflow integration', 'Deploy status visibility'],
        iconColor: 'text-amber-600 dark:text-amber-400',
        bgColor: 'bg-amber-100 dark:bg-amber-950/50',
        glowColor: 'from-amber-500/20 to-orange-500/10',
    },
    {
        id: 'collaboration',
        icon: MessageSquare,
        title: 'Real-time Collaboration',
        description: 'Work together seamlessly with comments, mentions, and instant notifications.',
        points: ['Threaded comments on tasks', '@mentions and notifications', 'File attachments and sharing', 'Activity feed and audit trail'],
        iconColor: 'text-rose-600 dark:text-rose-400',
        bgColor: 'bg-rose-100 dark:bg-rose-950/50',
        glowColor: 'from-rose-500/20 to-pink-500/10',
    },
    {
        id: 'analytics',
        icon: BarChart3,
        title: 'Analytics & Reporting',
        description: 'Get actionable insights into your team\'s productivity with comprehensive dashboards and reports.',
        points: ['Team velocity and throughput metrics', 'Cycle time and lead time tracking', 'Custom dashboard widgets', 'Export reports to CSV or PDF'],
        iconColor: 'text-cyan-600 dark:text-cyan-400',
        bgColor: 'bg-cyan-100 dark:bg-cyan-950/50',
        glowColor: 'from-cyan-500/20 to-sky-500/10',
    },
];
</script>

<template>
    <Head title="Features — DevCollab" />

    <MarketingLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-b from-indigo-500/10 to-transparent blur-3xl" />
            </div>
            <div
                data-animate="hero"
                class="mx-auto max-w-3xl px-4 text-center transition-all duration-700 sm:px-6"
                :class="isVisible('hero') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                    <Sparkles class="h-3.5 w-3.5" />
                    Features
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing: -0.02em">
                    Everything your team
                    <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">needs</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground" style="line-height: 1.7">
                    Powerful features designed to help your team collaborate, organize, and deliver projects on time. No bloat, no complexity.
                </p>
            </div>
        </section>

        <!-- Feature Deep-dives -->
        <section
            v-for="(feature, index) in features"
            :key="feature.id"
            :id="feature.id"
            class="relative overflow-hidden py-20 sm:py-28"
            :class="index % 2 === 0 ? '' : 'bg-muted/30'"
        >
            <div class="absolute inset-0 -z-10">
                <div
                    class="absolute h-[400px] w-[400px] rounded-full blur-3xl"
                    :class="[
                        `bg-gradient-to-br ${feature.glowColor}`,
                        index % 2 === 0 ? 'right-0 top-1/4' : 'left-0 top-1/4',
                    ]"
                />
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    :data-animate="`feature-${index}`"
                    class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16 transition-all duration-700"
                    :class="[
                        isVisible(`feature-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
                        index % 2 !== 0 ? 'lg:[direction:rtl]' : '',
                    ]"
                >
                    <!-- Text -->
                    <div :class="index % 2 !== 0 ? 'lg:[direction:ltr]' : ''">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl" :class="feature.bgColor">
                            <component :is="feature.icon" class="h-7 w-7" :class="feature.iconColor" />
                        </div>
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                            {{ feature.title }}
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-muted-foreground">
                            {{ feature.description }}
                        </p>
                        <ul class="mt-8 space-y-3">
                            <li
                                v-for="point in feature.points"
                                :key="point"
                                class="flex items-start gap-3 text-sm"
                            >
                                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-primary">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-muted-foreground">{{ point }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Illustration Placeholder -->
                    <div :class="index % 2 !== 0 ? 'lg:[direction:ltr]' : ''">
                        <div class="overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl">
                            <div class="flex items-center gap-2 border-b border-border/50 bg-muted/50 px-4 py-3">
                                <div class="h-3 w-3 rounded-full bg-red-400/80" />
                                <div class="h-3 w-3 rounded-full bg-yellow-400/80" />
                                <div class="h-3 w-3 rounded-full bg-green-400/80" />
                                <span class="ml-2 text-xs text-muted-foreground">{{ feature.title }}</span>
                            </div>
                            <div class="p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg" :class="feature.bgColor">
                                        <component :is="feature.icon" class="h-5 w-5" :class="feature.iconColor" />
                                    </div>
                                    <div>
                                        <div class="h-4 w-32 rounded bg-foreground/10" />
                                        <div class="mt-2 h-3 w-48 rounded bg-muted-foreground/10" />
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-10 w-full rounded-lg bg-muted/50" />
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="h-20 rounded-lg bg-muted/30" />
                                        <div class="h-20 rounded-lg bg-muted/30" />
                                    </div>
                                    <div class="h-8 w-2/3 rounded-lg bg-primary/10" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden py-24 sm:py-32">
            <div class="absolute inset-0 -z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-background via-primary/5 to-background" />
            </div>
            <div
                data-animate="cta"
                class="mx-auto max-w-3xl px-4 text-center transition-all duration-700 sm:px-6"
                :class="isVisible('cta') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                    Ready to get started?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    Try DevCollab free and see how it transforms your team's workflow.
                </p>
                <div class="mt-10">
                    <Button size="lg" as-child class="h-12 bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30">
                        <Link :href="register()">
                            Start Building Free
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
