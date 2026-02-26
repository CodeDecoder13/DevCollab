<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ArrowRight,
    Building2,
    Check,
    Code2,
    GitBranch,
    Layers,
    LineChart,
    Minus,
    Rocket,
    Shield,
    Target,
    Users,
    Zap,
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

const solutions = [
    {
        id: 'engineering',
        icon: Zap,
        title: 'Engineering Teams',
        description: 'Ship code faster with streamlined workflows designed for modern development teams.',
        benefits: ['Sprint planning and backlog management', 'Git integration with PR tracking', 'Automated workflows and CI/CD visibility', 'Code review assignment and tracking'],
        color: 'from-indigo-500 to-blue-500',
        bgColor: 'bg-indigo-100 dark:bg-indigo-950/50',
        iconColor: 'text-indigo-600 dark:text-indigo-400',
        glowColor: 'from-indigo-500/20 to-blue-500/10',
    },
    {
        id: 'product',
        icon: Rocket,
        title: 'Product Teams',
        description: 'Plan, prioritize, and track product roadmaps with full visibility into development progress.',
        benefits: ['Roadmap planning and visualization', 'Feature request tracking and voting', 'Cross-team dependency management', 'Release planning and changelogs'],
        color: 'from-violet-500 to-purple-500',
        bgColor: 'bg-violet-100 dark:bg-violet-950/50',
        iconColor: 'text-violet-600 dark:text-violet-400',
        glowColor: 'from-violet-500/20 to-purple-500/10',
    },
    {
        id: 'startups',
        icon: Activity,
        title: 'Startups',
        description: 'Move fast without losing track. Lightweight project management that scales with your team.',
        benefits: ['Quick setup in under 5 minutes', 'Free tier for small teams', 'Flexible workflows for rapid iteration', 'Simple onboarding for new team members'],
        color: 'from-emerald-500 to-teal-500',
        bgColor: 'bg-emerald-100 dark:bg-emerald-950/50',
        iconColor: 'text-emerald-600 dark:text-emerald-400',
        glowColor: 'from-emerald-500/20 to-teal-500/10',
    },
    {
        id: 'enterprise',
        icon: Building2,
        title: 'Enterprise',
        description: 'Scale project management across your organization with advanced security and administration.',
        benefits: ['SSO and advanced access controls', 'Audit logs and compliance features', 'Dedicated support and SLAs', 'Custom integrations and API access'],
        color: 'from-amber-500 to-orange-500',
        bgColor: 'bg-amber-100 dark:bg-amber-950/50',
        iconColor: 'text-amber-600 dark:text-amber-400',
        glowColor: 'from-amber-500/20 to-orange-500/10',
    },
];

const comparisonFeatures = [
    { name: 'Projects', free: '3', pro: 'Unlimited', enterprise: 'Unlimited' },
    { name: 'Team Members', free: '2', pro: '10', enterprise: 'Unlimited' },
    { name: 'Task Management', free: true, pro: true, enterprise: true },
    { name: 'Kanban Boards', free: true, pro: true, enterprise: true },
    { name: 'Sprint Planning', free: false, pro: true, enterprise: true },
    { name: 'Git Integration', free: false, pro: true, enterprise: true },
    { name: 'File Attachments', free: false, pro: true, enterprise: true },
    { name: 'Advanced Analytics', free: false, pro: false, enterprise: true },
    { name: 'Custom Integrations', free: false, pro: false, enterprise: true },
    { name: 'SSO & Access Controls', free: false, pro: false, enterprise: true },
    { name: 'Priority Support', free: false, pro: true, enterprise: true },
    { name: 'Dedicated Support', free: false, pro: false, enterprise: true },
];

const heroStats = [
    { value: '10K+', label: 'Teams' },
    { value: '50+', label: 'Countries' },
    { value: '99.9%', label: 'Uptime' },
    { value: '4.9/5', label: 'Rating' },
];
</script>

<template>
    <Head title="Solutions — DevCollab" />

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
                    <Target class="h-3.5 w-3.5" />
                    Solutions
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing: -0.02em">
                    Built for every
                    <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">team</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground" style="line-height: 1.7">
                    Whether you're a startup or an enterprise, DevCollab adapts to how your team works.
                </p>

                <!-- Hero Stats -->
                <div class="mx-auto mt-10 grid max-w-lg grid-cols-4 gap-6">
                    <div v-for="stat in heroStats" :key="stat.label" class="text-center">
                        <div class="text-2xl font-extrabold tracking-tight sm:text-3xl">{{ stat.value }}</div>
                        <div class="mt-0.5 text-xs text-muted-foreground">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solution Showcases -->
        <section
            v-for="(solution, index) in solutions"
            :key="solution.id"
            :id="solution.id"
            class="relative overflow-hidden py-20 sm:py-28"
            :class="index % 2 === 0 ? '' : 'bg-muted/30'"
        >
            <div class="absolute inset-0 -z-10">
                <div
                    class="absolute h-[400px] w-[400px] rounded-full blur-3xl"
                    :class="[
                        `bg-gradient-to-br ${solution.glowColor}`,
                        index % 2 === 0 ? 'right-0 top-1/4' : 'left-0 top-1/4',
                    ]"
                />
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    :data-animate="`solution-${index}`"
                    class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16 transition-all duration-700"
                    :class="[
                        isVisible(`solution-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
                        index % 2 !== 0 ? 'lg:[direction:rtl]' : '',
                    ]"
                >
                    <!-- Text -->
                    <div :class="index % 2 !== 0 ? 'lg:[direction:ltr]' : ''">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl" :class="solution.bgColor">
                            <component :is="solution.icon" class="h-7 w-7" :class="solution.iconColor" />
                        </div>
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                            {{ solution.title }}
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-muted-foreground">
                            {{ solution.description }}
                        </p>
                        <ul class="mt-8 space-y-3">
                            <li
                                v-for="benefit in solution.benefits"
                                :key="benefit"
                                class="flex items-start gap-3 text-sm"
                            >
                                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gradient-to-br text-white" :class="solution.color">
                                    <Check class="h-3 w-3" />
                                </div>
                                <span class="text-muted-foreground">{{ benefit }}</span>
                            </li>
                        </ul>
                        <div class="mt-8">
                            <Button size="sm" as-child variant="outline" class="gap-2">
                                <Link :href="register()">
                                    Get started
                                    <ArrowRight class="h-3.5 w-3.5" />
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <!-- Themed Mockups -->
                    <div :class="index % 2 !== 0 ? 'lg:[direction:ltr]' : ''">
                        <div class="overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl">
                            <div class="flex items-center gap-2 border-b border-border/50 bg-muted/50 px-4 py-3">
                                <div class="h-3 w-3 rounded-full bg-red-400/80" />
                                <div class="h-3 w-3 rounded-full bg-yellow-400/80" />
                                <div class="h-3 w-3 rounded-full bg-green-400/80" />
                                <div class="ml-3 flex-1 rounded-md bg-background/60 px-3 py-1 text-xs text-muted-foreground">
                                    {{ solution.title }}
                                </div>
                            </div>

                            <!-- Engineering Team Mockup -->
                            <div v-if="solution.id === 'engineering'" class="p-4 sm:p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <div>
                                        <div class="text-xs font-medium text-foreground">Sprint 14 — Active</div>
                                        <div class="mt-1 text-[10px] text-muted-foreground">8 of 12 story points completed</div>
                                    </div>
                                    <div class="rounded-lg bg-indigo-100 px-2 py-1 text-[10px] font-medium text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">67%</div>
                                </div>
                                <div class="mb-4 h-2 w-full overflow-hidden rounded-full bg-muted">
                                    <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500" />
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-1 pb-1">
                                            <div class="h-1.5 w-1.5 rounded-full bg-blue-500" />
                                            <div class="text-[9px] font-medium text-muted-foreground">To Do</div>
                                        </div>
                                        <div class="rounded border border-border/50 bg-background p-1.5">
                                            <div class="h-1.5 w-4/5 rounded bg-foreground/10" />
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="rounded bg-indigo-100 px-1 py-0.5 text-[7px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">3 pts</div>
                                                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            </div>
                                        </div>
                                        <div class="rounded border border-border/50 bg-background p-1.5">
                                            <div class="h-1.5 w-3/5 rounded bg-foreground/10" />
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="rounded bg-indigo-100 px-1 py-0.5 text-[7px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">1 pt</div>
                                                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-1 pb-1">
                                            <div class="h-1.5 w-1.5 rounded-full bg-amber-500" />
                                            <div class="text-[9px] font-medium text-muted-foreground">In Progress</div>
                                        </div>
                                        <div class="rounded border-2 border-primary/30 bg-primary/5 p-1.5">
                                            <div class="h-1.5 w-full rounded bg-foreground/10" />
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="rounded bg-indigo-100 px-1 py-0.5 text-[7px] text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">5 pts</div>
                                                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-rose-500 to-pink-500" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-1 pb-1">
                                            <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                            <div class="text-[9px] font-medium text-muted-foreground">Done</div>
                                        </div>
                                        <div class="rounded border border-border/50 bg-background p-1.5 opacity-60">
                                            <div class="h-1.5 w-3/4 rounded bg-foreground/10" />
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="rounded bg-emerald-100 px-1 py-0.5 text-[7px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">3 pts</div>
                                                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            </div>
                                        </div>
                                        <div class="rounded border border-border/50 bg-background p-1.5 opacity-60">
                                            <div class="h-1.5 w-2/3 rounded bg-foreground/10" />
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="rounded bg-emerald-100 px-1 py-0.5 text-[7px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">5 pts</div>
                                                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-amber-500 to-orange-500" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Team Mockup -->
                            <div v-else-if="solution.id === 'product'" class="p-4 sm:p-6">
                                <div class="mb-3 text-xs font-medium text-foreground">Product Roadmap — Q1 2026</div>
                                <!-- Timeline -->
                                <div class="space-y-3">
                                    <div class="relative">
                                        <div class="flex items-start gap-3">
                                            <div class="flex flex-col items-center">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                                    <Check class="h-3 w-3 text-emerald-600 dark:text-emerald-400" />
                                                </div>
                                                <div class="mt-1 h-8 w-px bg-border" />
                                            </div>
                                            <div class="flex-1 pb-2">
                                                <div class="text-[10px] font-medium text-foreground">Auth Redesign</div>
                                                <div class="mt-0.5 text-[9px] text-muted-foreground">Completed — Jan 15</div>
                                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                                    <div class="h-full w-full rounded-full bg-emerald-500" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-start gap-3">
                                            <div class="flex flex-col items-center">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-950/50">
                                                    <div class="h-2 w-2 rounded-full bg-violet-500" />
                                                </div>
                                                <div class="mt-1 h-8 w-px bg-border" />
                                            </div>
                                            <div class="flex-1 pb-2">
                                                <div class="text-[10px] font-medium text-foreground">Analytics Dashboard</div>
                                                <div class="mt-0.5 text-[9px] text-muted-foreground">In Progress — 65%</div>
                                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                                    <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-violet-500 to-purple-500" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-start gap-3">
                                            <div class="flex flex-col items-center">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-muted">
                                                    <div class="h-2 w-2 rounded-full bg-muted-foreground/40" />
                                                </div>
                                                <div class="mt-1 h-8 w-px bg-border" />
                                            </div>
                                            <div class="flex-1 pb-2">
                                                <div class="text-[10px] font-medium text-foreground">API v2 Launch</div>
                                                <div class="mt-0.5 text-[9px] text-muted-foreground">Planned — Mar 1</div>
                                                <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                                    <div class="h-full w-0 rounded-full bg-muted-foreground/30" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-muted">
                                                <div class="h-2 w-2 rounded-full bg-muted-foreground/40" />
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-[10px] font-medium text-foreground">Mobile App Beta</div>
                                                <div class="mt-0.5 text-[9px] text-muted-foreground">Planned — Mar 15</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Startup Mockup -->
                            <div v-else-if="solution.id === 'startups'" class="p-4 sm:p-6">
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="text-xs font-medium text-foreground">Team Dashboard</div>
                                    <div class="flex -space-x-1.5">
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 ring-2 ring-card text-[7px] flex items-center justify-center text-white font-bold">JK</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 ring-2 ring-card text-[7px] flex items-center justify-center text-white font-bold">AL</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 ring-2 ring-card text-[7px] flex items-center justify-center text-white font-bold">+2</div>
                                    </div>
                                </div>
                                <!-- Quick stats -->
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="text-[9px] text-muted-foreground">Open Tasks</div>
                                        <div class="text-lg font-bold">14</div>
                                        <div class="mt-0.5 text-[8px] text-emerald-500">-3 from last week</div>
                                    </div>
                                    <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="text-[9px] text-muted-foreground">Completed</div>
                                        <div class="text-lg font-bold text-emerald-500">28</div>
                                        <div class="mt-0.5 text-[8px] text-emerald-500">+12 this week</div>
                                    </div>
                                </div>
                                <!-- Recent activity -->
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="text-[9px] font-medium text-muted-foreground mb-2">Recent Activity</div>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                            <div class="flex-1 text-[9px] text-muted-foreground"><span class="font-medium text-foreground">JK</span> completed "Setup CI pipeline"</div>
                                            <div class="text-[8px] text-muted-foreground/60">2m</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                            <div class="flex-1 text-[9px] text-muted-foreground"><span class="font-medium text-foreground">AL</span> created "Design landing page"</div>
                                            <div class="text-[8px] text-muted-foreground/60">5m</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded-full bg-gradient-to-br from-amber-500 to-orange-500" />
                                            <div class="flex-1 text-[9px] text-muted-foreground"><span class="font-medium text-foreground">RW</span> commented on "API endpoints"</div>
                                            <div class="text-[8px] text-muted-foreground/60">12m</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enterprise Mockup -->
                            <div v-else-if="solution.id === 'enterprise'" class="p-4 sm:p-6">
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="text-xs font-medium text-foreground">Organization Overview</div>
                                    <div class="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-medium text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">Enterprise</div>
                                </div>
                                <!-- Org stats -->
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <div class="rounded-lg border border-border/50 bg-background p-2 text-center">
                                        <div class="text-sm font-bold">12</div>
                                        <div class="text-[8px] text-muted-foreground">Teams</div>
                                    </div>
                                    <div class="rounded-lg border border-border/50 bg-background p-2 text-center">
                                        <div class="text-sm font-bold">148</div>
                                        <div class="text-[8px] text-muted-foreground">Members</div>
                                    </div>
                                    <div class="rounded-lg border border-border/50 bg-background p-2 text-center">
                                        <div class="text-sm font-bold">34</div>
                                        <div class="text-[8px] text-muted-foreground">Projects</div>
                                    </div>
                                </div>
                                <!-- Security features -->
                                <div class="rounded-lg border border-border/50 bg-background p-2.5 mb-2">
                                    <div class="text-[9px] font-medium text-muted-foreground mb-2">Security & Compliance</div>
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                                <Check class="h-2.5 w-2.5 text-emerald-600 dark:text-emerald-400" />
                                            </div>
                                            <span class="text-[9px] text-foreground">SAML SSO Enabled</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                                <Check class="h-2.5 w-2.5 text-emerald-600 dark:text-emerald-400" />
                                            </div>
                                            <span class="text-[9px] text-foreground">Audit Logging Active</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                                <Check class="h-2.5 w-2.5 text-emerald-600 dark:text-emerald-400" />
                                            </div>
                                            <span class="text-[9px] text-foreground">2FA Enforced</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-lg border border-border/50 bg-background p-2.5">
                                    <div class="flex items-center justify-between">
                                        <div class="text-[9px] font-medium text-muted-foreground">API Usage</div>
                                        <div class="text-[9px] text-muted-foreground">42K / 100K</div>
                                    </div>
                                    <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                        <div class="h-full w-[42%] rounded-full bg-gradient-to-r from-amber-500 to-orange-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Comparison Table -->
        <section class="relative overflow-hidden py-24 sm:py-32">
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-b from-indigo-500/5 to-transparent blur-3xl" />
            </div>
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="comparison-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('comparison-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                        <LineChart class="h-3.5 w-3.5" />
                        Compare Plans
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        Find the right plan for your team
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Every plan includes the essentials. Upgrade as your team grows.
                    </p>
                </div>

                <div
                    data-animate="comparison-table"
                    class="transition-all duration-700"
                    :class="isVisible('comparison-table') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="overflow-hidden rounded-2xl border border-border/50 bg-card">
                        <!-- Header -->
                        <div class="grid grid-cols-4 border-b border-border/50 bg-muted/50">
                            <div class="p-4 sm:p-5">
                                <span class="text-sm font-medium text-muted-foreground">Features</span>
                            </div>
                            <div class="p-4 text-center sm:p-5">
                                <div class="text-sm font-semibold">Free</div>
                                <div class="text-xs text-muted-foreground">$0/mo</div>
                            </div>
                            <div class="relative p-4 text-center sm:p-5">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 to-violet-500" />
                                <div class="text-sm font-semibold">Pro</div>
                                <div class="text-xs text-muted-foreground">$12/mo</div>
                            </div>
                            <div class="p-4 text-center sm:p-5">
                                <div class="text-sm font-semibold">Enterprise</div>
                                <div class="text-xs text-muted-foreground">$39/mo</div>
                            </div>
                        </div>
                        <!-- Rows -->
                        <div
                            v-for="(row, rowIndex) in comparisonFeatures"
                            :key="row.name"
                            class="grid grid-cols-4 border-b border-border/30 last:border-b-0"
                            :class="rowIndex % 2 === 0 ? '' : 'bg-muted/20'"
                        >
                            <div class="flex items-center p-3 sm:p-4">
                                <span class="text-xs sm:text-sm text-foreground">{{ row.name }}</span>
                            </div>
                            <div class="flex items-center justify-center p-3 sm:p-4">
                                <template v-if="typeof row.free === 'boolean'">
                                    <div v-if="row.free" class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                        <Check class="h-3 w-3 text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                    <Minus v-else class="h-4 w-4 text-muted-foreground/30" />
                                </template>
                                <span v-else class="text-xs sm:text-sm font-medium text-foreground">{{ row.free }}</span>
                            </div>
                            <div class="flex items-center justify-center p-3 sm:p-4">
                                <template v-if="typeof row.pro === 'boolean'">
                                    <div v-if="row.pro" class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                        <Check class="h-3 w-3 text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                    <Minus v-else class="h-4 w-4 text-muted-foreground/30" />
                                </template>
                                <span v-else class="text-xs sm:text-sm font-medium text-foreground">{{ row.pro }}</span>
                            </div>
                            <div class="flex items-center justify-center p-3 sm:p-4">
                                <template v-if="typeof row.enterprise === 'boolean'">
                                    <div v-if="row.enterprise" class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50">
                                        <Check class="h-3 w-3 text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                    <Minus v-else class="h-4 w-4 text-muted-foreground/30" />
                                </template>
                                <span v-else class="text-xs sm:text-sm font-medium text-foreground">{{ row.enterprise }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table CTA -->
                    <div class="mt-8 text-center">
                        <Button size="lg" as-child class="h-12 bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30">
                            <Link :href="register()">
                                Start Free Trial
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted By / Social Proof -->
        <section class="relative overflow-hidden border-y border-border/50 bg-muted/30 py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="trusted"
                    class="text-center transition-all duration-700"
                    :class="isVisible('trusted') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex -space-x-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-xs font-semibold text-white ring-2 ring-background">JK</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 text-xs font-semibold text-white ring-2 ring-background">AL</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-xs font-semibold text-white ring-2 ring-background">RW</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-xs font-semibold text-white ring-2 ring-background">SC</div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 text-xs font-semibold text-white ring-2 ring-background">+</div>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-bold tracking-tight sm:text-2xl" style="letter-spacing: -0.02em">
                        Trusted by <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">10,000+</span> teams worldwide
                    </h3>
                    <p class="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                        From 2-person startups to Fortune 500 companies, teams choose DevCollab to deliver faster.
                    </p>

                    <div class="mx-auto mt-8 grid max-w-2xl grid-cols-2 gap-6 sm:grid-cols-4">
                        <div class="flex flex-col items-center gap-1">
                            <Users class="h-5 w-5 text-primary" />
                            <div class="text-2xl font-extrabold tracking-tight">10K+</div>
                            <div class="text-xs text-muted-foreground">Teams</div>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <Code2 class="h-5 w-5 text-primary" />
                            <div class="text-2xl font-extrabold tracking-tight">2M+</div>
                            <div class="text-xs text-muted-foreground">Tasks Created</div>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <Shield class="h-5 w-5 text-primary" />
                            <div class="text-2xl font-extrabold tracking-tight">99.9%</div>
                            <div class="text-xs text-muted-foreground">Uptime</div>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <GitBranch class="h-5 w-5 text-primary" />
                            <div class="text-2xl font-extrabold tracking-tight">500K+</div>
                            <div class="text-xs text-muted-foreground">Commits Linked</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden py-24 sm:py-32">
            <div class="absolute inset-0 -z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent" />
            </div>
            <div
                data-animate="cta"
                class="mx-auto max-w-3xl px-4 text-center transition-all duration-700 sm:px-6"
                :class="isVisible('cta') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                    Not sure which plan fits?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    Talk to our team and we'll help you find the perfect setup for your organization.
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Button size="lg" as-child class="h-12 bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30">
                        <Link :href="register()">
                            Start Free Trial
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                    <Button size="lg" variant="outline" as-child class="h-12 px-8 text-base">
                        <Link href="/contact">Contact Sales</Link>
                    </Button>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
