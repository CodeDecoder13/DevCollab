<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ArrowRight,
    Building2,
    Check,
    Rocket,
    Target,
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
    },
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
            </div>
        </section>

        <!-- Solution Cards -->
        <section class="pb-24 sm:pb-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-2">
                    <div
                        v-for="(solution, index) in solutions"
                        :key="solution.id"
                        :id="solution.id"
                        :data-animate="`solution-${index}`"
                        class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card transition-all duration-500 hover:-translate-y-1 hover:border-primary/20 hover:shadow-xl"
                        :class="isVisible(`solution-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <!-- Gradient accent top -->
                        <div class="h-1 w-full bg-gradient-to-r" :class="solution.color" />

                        <div class="p-8">
                            <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-xl" :class="solution.bgColor">
                                <component :is="solution.icon" class="h-7 w-7" :class="solution.iconColor" />
                            </div>
                            <h3 class="text-2xl font-bold tracking-tight">{{ solution.title }}</h3>
                            <p class="mt-3 text-muted-foreground leading-relaxed">{{ solution.description }}</p>
                            <ul class="mt-6 space-y-3">
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
                                <Link
                                    :href="register()"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary transition-colors hover:text-primary/80"
                                >
                                    Get started
                                    <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden bg-muted/30 py-24 sm:py-32">
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
