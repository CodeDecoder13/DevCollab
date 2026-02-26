<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BarChart3,
    CheckSquare,
    Code2,
    FolderKanban,
    GitBranch,
    GitPullRequest,
    Layers,
    MessageSquare,
    Sparkles,
    TrendingUp,
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
        shortDesc: 'Visual drag-and-drop workflows',
        description: 'Visualize your entire workflow with intuitive drag-and-drop kanban boards that adapt to your process.',
        points: ['Drag-and-drop task cards between columns', 'Custom column names and WIP limits', 'Filter and search across boards', 'Color-coded labels and priorities'],
        iconColor: 'text-indigo-600 dark:text-indigo-400',
        bgColor: 'bg-indigo-100 dark:bg-indigo-950/50',
        glowColor: 'from-indigo-500/20 to-blue-500/10',
        accentColor: 'from-indigo-500 to-blue-500',
    },
    {
        id: 'sprints',
        icon: Layers,
        title: 'Sprint Planning',
        shortDesc: 'Agile sprint management tools',
        description: 'Plan and manage agile sprints with powerful planning tools designed for scrum and kanban teams.',
        points: ['Sprint backlogs with story points', 'Velocity tracking and burn-down charts', 'Sprint retrospective summaries', 'Automated sprint rollover'],
        iconColor: 'text-violet-600 dark:text-violet-400',
        bgColor: 'bg-violet-100 dark:bg-violet-950/50',
        glowColor: 'from-violet-500/20 to-purple-500/10',
        accentColor: 'from-violet-500 to-purple-500',
    },
    {
        id: 'issues',
        icon: CheckSquare,
        title: 'Issue Tracking',
        shortDesc: 'Track bugs and feature requests',
        description: 'Track bugs, feature requests, and tasks with full context. Never lose sight of what needs to be done.',
        points: ['Custom issue types and workflows', 'Priority levels and due dates', 'Sub-tasks and dependencies', 'Bulk actions and quick filters'],
        iconColor: 'text-emerald-600 dark:text-emerald-400',
        bgColor: 'bg-emerald-100 dark:bg-emerald-950/50',
        glowColor: 'from-emerald-500/20 to-teal-500/10',
        accentColor: 'from-emerald-500 to-teal-500',
    },
    {
        id: 'git',
        icon: GitBranch,
        title: 'Git Integration',
        shortDesc: 'Code changes linked to tasks',
        description: 'Connect your repositories and see code changes linked directly to tasks and issues.',
        points: ['Link commits to tasks automatically', 'Branch and PR status tracking', 'Code review workflow integration', 'Deploy status visibility'],
        iconColor: 'text-amber-600 dark:text-amber-400',
        bgColor: 'bg-amber-100 dark:bg-amber-950/50',
        glowColor: 'from-amber-500/20 to-orange-500/10',
        accentColor: 'from-amber-500 to-orange-500',
    },
    {
        id: 'collaboration',
        icon: MessageSquare,
        title: 'Real-time Collaboration',
        shortDesc: 'Comments, mentions, and notifications',
        description: 'Work together seamlessly with comments, mentions, and instant notifications.',
        points: ['Threaded comments on tasks', '@mentions and notifications', 'File attachments and sharing', 'Activity feed and audit trail'],
        iconColor: 'text-rose-600 dark:text-rose-400',
        bgColor: 'bg-rose-100 dark:bg-rose-950/50',
        glowColor: 'from-rose-500/20 to-pink-500/10',
        accentColor: 'from-rose-500 to-pink-500',
    },
    {
        id: 'analytics',
        icon: BarChart3,
        title: 'Analytics & Reporting',
        shortDesc: 'Actionable team insights',
        description: 'Get actionable insights into your team\'s productivity with comprehensive dashboards and reports.',
        points: ['Team velocity and throughput metrics', 'Cycle time and lead time tracking', 'Custom dashboard widgets', 'Export reports to CSV or PDF'],
        iconColor: 'text-cyan-600 dark:text-cyan-400',
        bgColor: 'bg-cyan-100 dark:bg-cyan-950/50',
        glowColor: 'from-cyan-500/20 to-sky-500/10',
        accentColor: 'from-cyan-500 to-sky-500',
    },
];

const integrations = [
    { name: 'GitHub', icon: GitBranch },
    { name: 'GitLab', icon: GitPullRequest },
    { name: 'Slack', icon: MessageSquare },
    { name: 'VS Code', icon: Code2 },
    { name: 'Linear', icon: Layers },
    { name: 'Datadog', icon: TrendingUp },
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

            <!-- Hero Mockup -->
            <div
                data-animate="hero-mockup"
                class="relative z-10 mx-auto mt-12 max-w-4xl px-4 transition-all duration-1000 sm:mt-16 sm:px-6"
                :class="isVisible('hero-mockup') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
            >
                <div class="overflow-hidden rounded-xl border border-border/50 bg-card shadow-2xl shadow-indigo-500/5">
                    <!-- Browser chrome -->
                    <div class="flex items-center gap-2 border-b border-border/50 bg-muted/50 px-4 py-3">
                        <div class="h-3 w-3 rounded-full bg-red-400/80" />
                        <div class="h-3 w-3 rounded-full bg-yellow-400/80" />
                        <div class="h-3 w-3 rounded-full bg-green-400/80" />
                        <div class="ml-3 flex-1 rounded-md bg-background/60 px-3 py-1 text-xs text-muted-foreground">
                            app.devcollab.io/projects/acme-redesign
                        </div>
                    </div>
                    <!-- Kanban board mockup -->
                    <div class="p-3 sm:p-6">
                        <div class="mb-4 flex items-center justify-between sm:mb-5">
                            <div>
                                <div class="h-4 w-28 rounded bg-foreground/10 sm:h-5 sm:w-40" />
                                <div class="mt-2 hidden h-3 w-56 rounded bg-muted-foreground/10 sm:block" />
                            </div>
                            <div class="flex gap-2">
                                <div class="hidden h-8 w-8 rounded-lg bg-primary/10 sm:block" />
                                <div class="h-7 w-20 rounded-lg bg-primary/20 sm:h-8 sm:w-24" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2 pb-2">
                                    <div class="h-2 w-2 rounded-full bg-muted-foreground/40" />
                                    <div class="text-xs font-medium text-muted-foreground">Backlog</div>
                                    <div class="ml-auto rounded-full bg-muted px-1.5 py-0.5 text-[10px] text-muted-foreground">4</div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="h-2.5 w-4/5 rounded bg-foreground/10" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-muted px-1.5 py-0.5 text-[9px] text-muted-foreground">Design</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                    </div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="h-2.5 w-3/5 rounded bg-foreground/10" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-amber-100 px-1.5 py-0.5 text-[9px] text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">Bug</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2 pb-2">
                                    <div class="h-2 w-2 rounded-full bg-blue-500" />
                                    <div class="text-xs font-medium text-muted-foreground">To Do</div>
                                    <div class="ml-auto rounded-full bg-muted px-1.5 py-0.5 text-[10px] text-muted-foreground">6</div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="h-2.5 w-full rounded bg-foreground/10" />
                                    <div class="mt-1.5 h-2 w-3/4 rounded bg-foreground/5" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-indigo-100 px-1.5 py-0.5 text-[9px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">Feature</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-rose-500 to-pink-500" />
                                    </div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="h-2.5 w-2/3 rounded bg-foreground/10" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-muted px-1.5 py-0.5 text-[9px] text-muted-foreground">Task</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-amber-500 to-orange-500" />
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2 pb-2">
                                    <div class="h-2 w-2 rounded-full bg-amber-500" />
                                    <div class="text-xs font-medium text-muted-foreground">In Progress</div>
                                    <div class="ml-auto rounded-full bg-muted px-1.5 py-0.5 text-[10px] text-muted-foreground">3</div>
                                </div>
                                <div class="rounded-lg border-2 border-primary/30 bg-primary/5 p-2.5">
                                    <div class="h-2.5 w-5/6 rounded bg-foreground/10" />
                                    <div class="mt-1.5 h-2 w-1/2 rounded bg-foreground/5" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-indigo-100 px-1.5 py-0.5 text-[9px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">Feature</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2 pb-2">
                                    <div class="h-2 w-2 rounded-full bg-emerald-500" />
                                    <div class="text-xs font-medium text-muted-foreground">Done</div>
                                    <div class="ml-auto rounded-full bg-muted px-1.5 py-0.5 text-[10px] text-muted-foreground">12</div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5 opacity-60">
                                    <div class="h-2.5 w-4/5 rounded bg-foreground/10" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-emerald-100 px-1.5 py-0.5 text-[9px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Done</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                    </div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5 opacity-60">
                                    <div class="h-2.5 w-2/3 rounded bg-foreground/10" />
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="rounded bg-emerald-100 px-1.5 py-0.5 text-[9px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Done</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Overview Bento Grid -->
        <section class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="overview-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('overview-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                        A toolkit built for
                        <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">shipping</span>
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Six powerful features that work together to keep your team aligned and productive.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a
                        v-for="(feature, index) in features"
                        :key="feature.id"
                        :href="`#${feature.id}`"
                        :data-animate="`grid-${index}`"
                        class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card p-6 transition-all duration-500 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :class="isVisible(`grid-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <!-- Gradient glow on hover -->
                        <div
                            class="absolute -right-12 -top-12 h-32 w-32 rounded-full opacity-0 blur-3xl transition-opacity duration-500 group-hover:opacity-100"
                            :class="`bg-gradient-to-br ${feature.glowColor}`"
                        />
                        <div
                            class="relative mb-4 flex h-12 w-12 items-center justify-center rounded-xl"
                            :class="feature.bgColor"
                        >
                            <component :is="feature.icon" class="h-6 w-6" :class="feature.iconColor" />
                        </div>
                        <h3 class="relative mb-2 text-lg font-semibold">{{ feature.title }}</h3>
                        <p class="relative text-sm leading-relaxed text-muted-foreground">{{ feature.shortDesc }}</p>
                        <div class="relative mt-4 inline-flex items-center gap-1 text-xs font-medium text-primary opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            Learn more
                            <ArrowRight class="h-3 w-3" />
                        </div>
                    </a>
                </div>
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
                    class="grid items-center gap-8 sm:gap-10 lg:grid-cols-2 lg:gap-16 transition-all duration-700"
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

                    <!-- Themed Mockup Illustrations -->
                    <div :class="index % 2 !== 0 ? 'lg:[direction:ltr]' : ''">
                        <div class="overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl">
                            <div class="flex items-center gap-2 border-b border-border/50 bg-muted/50 px-4 py-3">
                                <div class="h-3 w-3 rounded-full bg-red-400/80" />
                                <div class="h-3 w-3 rounded-full bg-yellow-400/80" />
                                <div class="h-3 w-3 rounded-full bg-green-400/80" />
                                <div class="ml-3 flex-1 rounded-md bg-background/60 px-3 py-1 text-xs text-muted-foreground">
                                    {{ feature.title }}
                                </div>
                            </div>

                            <!-- Kanban Board Mockup -->
                            <div v-if="feature.id === 'kanban'" class="p-3 sm:p-6">
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-3">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 pb-1">
                                            <div class="h-2 w-2 rounded-full bg-blue-500" />
                                            <div class="text-[10px] font-medium text-muted-foreground">To Do</div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2">
                                            <div class="h-2 w-4/5 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-indigo-100 px-1 py-0.5 text-[8px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">Feature</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            </div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2">
                                            <div class="h-2 w-3/5 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-amber-100 px-1 py-0.5 text-[8px] text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">Bug</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                            </div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2">
                                            <div class="h-2 w-2/3 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-muted px-1 py-0.5 text-[8px] text-muted-foreground">Task</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-rose-500 to-pink-500" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 pb-1">
                                            <div class="h-2 w-2 rounded-full bg-amber-500" />
                                            <div class="text-[10px] font-medium text-muted-foreground">In Progress</div>
                                        </div>
                                        <div class="rounded-lg border-2 border-primary/30 bg-primary/5 p-2">
                                            <div class="h-2 w-full rounded bg-foreground/10" />
                                            <div class="mt-1 h-1.5 w-2/3 rounded bg-foreground/5" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-indigo-100 px-1 py-0.5 text-[8px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">Feature</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-amber-500 to-orange-500" />
                                            </div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2">
                                            <div class="h-2 w-4/5 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-red-100 px-1 py-0.5 text-[8px] text-red-600 dark:bg-red-950/50 dark:text-red-400">Urgent</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden space-y-2 sm:block">
                                        <div class="flex items-center gap-2 pb-1">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500" />
                                            <div class="text-[10px] font-medium text-muted-foreground">Done</div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2 opacity-60">
                                            <div class="h-2 w-3/4 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-emerald-100 px-1 py-0.5 text-[8px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Done</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                            </div>
                                        </div>
                                        <div class="rounded-lg border border-border/50 bg-background p-2 opacity-60">
                                            <div class="h-2 w-2/3 rounded bg-foreground/10" />
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <div class="rounded bg-emerald-100 px-1 py-0.5 text-[8px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Done</div>
                                                <div class="ml-auto h-4 w-4 rounded-full bg-gradient-to-br from-violet-500 to-purple-500" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sprint Planning Mockup -->
                            <div v-else-if="feature.id === 'sprints'" class="p-4 sm:p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <div class="text-xs font-medium text-muted-foreground">Sprint 14 — Week 2 of 2</div>
                                    <div class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-medium text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">8 of 12 pts</div>
                                </div>
                                <!-- Burndown chart mockup -->
                                <div class="mb-4 rounded-lg border border-border/50 bg-background p-3">
                                    <div class="flex items-end gap-1" style="height: 80px;">
                                        <div class="flex-1 rounded-t bg-violet-500/20" style="height: 100%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/25" style="height: 85%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/30" style="height: 72%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/35" style="height: 60%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/40" style="height: 50%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/50" style="height: 40%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/60" style="height: 30%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/15" style="height: 25%;" />
                                        <div class="flex-1 rounded-t bg-violet-500/10" style="height: 18%;" />
                                        <div class="flex-1 rounded-t bg-muted/30" style="height: 10%;" />
                                    </div>
                                    <div class="mt-1 flex justify-between text-[8px] text-muted-foreground">
                                        <span>Day 1</span>
                                        <span>Day 10</span>
                                    </div>
                                </div>
                                <!-- Sprint items -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2 rounded-md bg-background p-2">
                                        <div class="h-3 w-3 rounded-sm border-2 border-emerald-400 bg-emerald-400 flex items-center justify-center">
                                            <svg class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <div class="h-2 w-2/3 rounded bg-foreground/8" />
                                        <div class="ml-auto rounded bg-violet-100 px-1 py-0.5 text-[8px] text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">3 pts</div>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-md bg-background p-2">
                                        <div class="h-3 w-3 rounded-sm border-2 border-emerald-400 bg-emerald-400 flex items-center justify-center">
                                            <svg class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <div class="h-2 w-1/2 rounded bg-foreground/8" />
                                        <div class="ml-auto rounded bg-violet-100 px-1 py-0.5 text-[8px] text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">2 pts</div>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-md border border-primary/20 bg-primary/5 p-2">
                                        <div class="h-3 w-3 rounded-sm border-2 border-amber-400" />
                                        <div class="h-2 w-3/4 rounded bg-foreground/8" />
                                        <div class="ml-auto rounded bg-violet-100 px-1 py-0.5 text-[8px] text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">5 pts</div>
                                    </div>
                                    <div class="flex items-center gap-2 rounded-md bg-background p-2">
                                        <div class="h-3 w-3 rounded-sm border-2 border-muted-foreground/30" />
                                        <div class="h-2 w-1/2 rounded bg-foreground/8" />
                                        <div class="ml-auto rounded bg-violet-100 px-1 py-0.5 text-[8px] text-violet-600 dark:bg-violet-950/50 dark:text-violet-400">2 pts</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Issue Tracking Mockup -->
                            <div v-else-if="feature.id === 'issues'" class="p-4 sm:p-6">
                                <div class="mb-3 flex items-center gap-2">
                                    <div class="h-7 flex-1 rounded-lg border border-border/50 bg-background px-2 flex items-center">
                                        <div class="text-[10px] text-muted-foreground">Search issues...</div>
                                    </div>
                                    <div class="rounded-lg bg-primary/15 px-2 py-1.5 text-[10px] font-medium text-primary">+ New</div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-950/50">
                                            <div class="h-2 w-2 rounded-full bg-red-500" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2.5 w-3/4 rounded bg-foreground/10" />
                                        </div>
                                        <div class="rounded bg-red-100 px-1.5 py-0.5 text-[8px] font-medium text-red-600 dark:bg-red-950/50 dark:text-red-400">Critical</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                    </div>
                                    <div class="flex items-center gap-2.5 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-950/50">
                                            <div class="h-2 w-2 rounded-full bg-amber-500" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2.5 w-full rounded bg-foreground/10" />
                                        </div>
                                        <div class="rounded bg-amber-100 px-1.5 py-0.5 text-[8px] font-medium text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">Medium</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                    </div>
                                    <div class="flex items-center gap-2.5 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-950/50">
                                            <div class="h-2 w-2 rounded-full bg-blue-500" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2.5 w-2/3 rounded bg-foreground/10" />
                                        </div>
                                        <div class="rounded bg-blue-100 px-1.5 py-0.5 text-[8px] font-medium text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">Low</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-rose-500 to-pink-500" />
                                    </div>
                                    <div class="flex items-center gap-2.5 rounded-lg border border-border/50 bg-background p-2.5 opacity-60">
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                            <svg class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2.5 w-1/2 rounded bg-foreground/8" />
                                        </div>
                                        <div class="rounded bg-emerald-100 px-1.5 py-0.5 text-[8px] font-medium text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Closed</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-amber-500 to-orange-500" />
                                    </div>
                                </div>
                            </div>

                            <!-- Git Integration Mockup -->
                            <div v-else-if="feature.id === 'git'" class="p-4 sm:p-6">
                                <div class="space-y-3">
                                    <!-- Branch -->
                                    <div class="rounded-lg border border-border/50 bg-background p-3">
                                        <div class="flex items-center gap-2 mb-2">
                                            <GitBranch class="h-3.5 w-3.5 text-amber-500" />
                                            <div class="text-[10px] font-mono font-medium text-foreground">feature/auth-redesign</div>
                                            <div class="ml-auto rounded-full bg-emerald-100 px-1.5 py-0.5 text-[8px] font-medium text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Active</div>
                                        </div>
                                        <div class="flex items-center gap-2 text-[9px] text-muted-foreground">
                                            <div class="h-4 w-4 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            <span>3 commits ahead</span>
                                            <span class="text-muted-foreground/50">|</span>
                                            <span>2 files changed</span>
                                        </div>
                                    </div>
                                    <!-- PR -->
                                    <div class="rounded-lg border border-emerald-200 bg-emerald-50/50 p-3 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <GitPullRequest class="h-3.5 w-3.5 text-emerald-500" />
                                            <div class="text-[10px] font-medium text-foreground">PR #142 — Redesign auth flow</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="rounded-full bg-emerald-100 px-1.5 py-0.5 text-[8px] font-medium text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Approved</div>
                                            <div class="rounded-full bg-emerald-100 px-1.5 py-0.5 text-[8px] font-medium text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">CI Passed</div>
                                            <div class="ml-auto flex -space-x-1">
                                                <div class="h-4 w-4 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 ring-1 ring-background" />
                                                <div class="h-4 w-4 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 ring-1 ring-background" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Commit list -->
                                    <div class="rounded-lg border border-border/50 bg-background p-3">
                                        <div class="text-[9px] font-medium text-muted-foreground mb-2">Recent commits</div>
                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-2 text-[9px]">
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                                <span class="font-mono text-foreground/70">a3f2d1c</span>
                                                <span class="text-muted-foreground truncate">Fix token refresh logic</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[9px]">
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                                <span class="font-mono text-foreground/70">b7e9f42</span>
                                                <span class="text-muted-foreground truncate">Add OAuth2 provider</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[9px]">
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                                <span class="font-mono text-foreground/70">c1d8a5e</span>
                                                <span class="text-muted-foreground truncate">Update auth middleware</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Collaboration Mockup -->
                            <div v-else-if="feature.id === 'collaboration'" class="p-4 sm:p-6">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-2.5">
                                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-[9px] font-bold text-white">SC</div>
                                        <div class="flex-1">
                                            <div class="rounded-lg rounded-tl-sm border border-border/50 bg-background p-2.5">
                                                <div class="mb-1 flex items-center gap-2">
                                                    <span class="text-[10px] font-semibold">Sarah Chen</span>
                                                    <span class="text-[9px] text-muted-foreground">2m ago</span>
                                                </div>
                                                <div class="text-[10px] leading-relaxed text-muted-foreground">Updated the auth flow mockups. <span class="font-medium text-primary">@Marcus</span> can you review?</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 text-[9px] font-bold text-white">MR</div>
                                        <div class="flex-1">
                                            <div class="rounded-lg rounded-tl-sm border border-border/50 bg-background p-2.5">
                                                <div class="mb-1 flex items-center gap-2">
                                                    <span class="text-[10px] font-semibold">Marcus Rivera</span>
                                                    <span class="text-[9px] text-muted-foreground">1m ago</span>
                                                </div>
                                                <div class="text-[10px] leading-relaxed text-muted-foreground">Looks great! Left a few suggestions on the token handling.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-[9px] font-bold text-white">EZ</div>
                                        <div class="flex-1">
                                            <div class="rounded-lg rounded-tl-sm border border-primary/20 bg-primary/5 p-2.5">
                                                <div class="mb-1 flex items-center gap-2">
                                                    <span class="text-[10px] font-semibold">Emily Zhang</span>
                                                    <span class="text-[9px] text-muted-foreground">just now</span>
                                                </div>
                                                <div class="text-[10px] leading-relaxed text-muted-foreground">Approved! Let's ship this in Sprint 14.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Typing indicator -->
                                    <div class="flex items-center gap-2 pl-9">
                                        <div class="flex gap-1">
                                            <div class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted-foreground/40" style="animation-delay: 0ms;" />
                                            <div class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted-foreground/40" style="animation-delay: 150ms;" />
                                            <div class="h-1.5 w-1.5 animate-bounce rounded-full bg-muted-foreground/40" style="animation-delay: 300ms;" />
                                        </div>
                                        <span class="text-[9px] text-muted-foreground">Alex is typing...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Analytics Mockup -->
                            <div v-else-if="feature.id === 'analytics'" class="p-3 sm:p-6">
                                <!-- Stats row -->
                                <div class="grid grid-cols-3 gap-1.5 mb-4 sm:gap-2">
                                    <div class="rounded-lg border border-border/50 bg-background p-2">
                                        <div class="text-[8px] text-muted-foreground">Velocity</div>
                                        <div class="text-sm font-bold text-foreground">42 pts</div>
                                        <div class="text-[8px] text-emerald-500">+12%</div>
                                    </div>
                                    <div class="rounded-lg border border-border/50 bg-background p-2">
                                        <div class="text-[8px] text-muted-foreground">Cycle Time</div>
                                        <div class="text-sm font-bold text-foreground">2.3d</div>
                                        <div class="text-[8px] text-emerald-500">-8%</div>
                                    </div>
                                    <div class="rounded-lg border border-border/50 bg-background p-2">
                                        <div class="text-[8px] text-muted-foreground">Throughput</div>
                                        <div class="text-sm font-bold text-foreground">18/wk</div>
                                        <div class="text-[8px] text-emerald-500">+5%</div>
                                    </div>
                                </div>
                                <!-- Chart area -->
                                <div class="rounded-lg border border-border/50 bg-background p-3">
                                    <div class="mb-2 flex items-center justify-between">
                                        <div class="text-[9px] font-medium text-muted-foreground">Weekly Throughput</div>
                                        <div class="flex gap-3">
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-cyan-500" />
                                                <span class="text-[8px] text-muted-foreground">Tasks</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-violet-500" />
                                                <span class="text-[8px] text-muted-foreground">Points</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-end gap-1.5" style="height: 80px;">
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/40" style="height: 45%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/30" style="height: 35%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/40" style="height: 55%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/30" style="height: 50%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/40" style="height: 40%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/30" style="height: 38%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/40" style="height: 70%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/30" style="height: 60%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/50" style="height: 65%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/40" style="height: 55%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/60" style="height: 85%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/50" style="height: 75%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/70" style="height: 90%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/60" style="height: 80%;" />
                                        </div>
                                        <div class="flex flex-1 items-end gap-px">
                                            <div class="flex-1 rounded-t bg-cyan-500/80" style="height: 100%;" />
                                            <div class="flex-1 rounded-t bg-violet-500/70" style="height: 90%;" />
                                        </div>
                                    </div>
                                    <div class="mt-1 flex justify-between text-[8px] text-muted-foreground">
                                        <span>W1</span>
                                        <span>W8</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Logos Strip -->
        <section class="relative overflow-hidden border-y border-border/50 bg-muted/30 py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="integrations"
                    class="text-center transition-all duration-700"
                    :class="isVisible('integrations') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Integrations</div>
                    <h3 class="text-xl font-bold tracking-tight sm:text-2xl" style="letter-spacing: -0.02em">
                        Works with your favorite tools
                    </h3>
                    <p class="mx-auto mt-2 max-w-lg text-sm text-muted-foreground">
                        Connect DevCollab with the tools your team already uses for a seamless workflow.
                    </p>
                    <div class="mt-10 flex flex-wrap items-center justify-center gap-5 sm:gap-8 lg:gap-12">
                        <div
                            v-for="integration in integrations"
                            :key="integration.name"
                            class="flex flex-col items-center gap-2 text-muted-foreground/60 transition-colors duration-300 hover:text-foreground"
                        >
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl border border-border/50 bg-card shadow-sm">
                                <component :is="integration.icon" class="h-6 w-6" />
                            </div>
                            <span class="text-xs font-medium">{{ integration.name }}</span>
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
