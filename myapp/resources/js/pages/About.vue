<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    Bug,
    Eye,
    Heart,
    Lightbulb,
    Rocket,
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

const values = [
    { icon: Zap, title: 'Simplicity', description: 'Clean, intuitive interfaces that stay out of your way and let you focus on building.', color: 'from-indigo-500 to-blue-500' },
    { icon: Users, title: 'Collaboration', description: 'Every feature is designed to bring teams closer together, no matter where they are.', color: 'from-emerald-500 to-teal-500' },
    { icon: Rocket, title: 'Speed', description: 'Fast load times, instant updates, and workflows optimized for developer velocity.', color: 'from-amber-500 to-orange-500' },
    { icon: Eye, title: 'Transparency', description: 'Full visibility into every project, task, and decision. No black boxes.', color: 'from-violet-500 to-purple-500' },
];

const team = [
    { name: 'Rhuzzel Paramio', role: 'Software Engineer', avatar: 'RP', color: 'from-indigo-500 to-violet-500', bio: 'Brings the best ideas to life and automates everything to make DevCollab easier to use while working on projects.' },
];
</script>

<template>
    <Head title="About — DevCollab" />

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
                    <Heart class="h-3.5 w-3.5" />
                    Our Story
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing: -0.02em">
                    About
                    <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">DevCollab</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground" style="line-height: 1.7">
                    We believe project management should empower teams, not slow them down. DevCollab was built by developers, for developers.
                </p>
            </div>
        </section>

        <!-- Mission -->
        <section class="relative overflow-hidden bg-muted/30 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div
                        data-animate="mission-text"
                        class="transition-all duration-700"
                        :class="isVisible('mission-text') ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                    >
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                            Why DevCollab exists
                        </h2>
                        <p class="mt-6 text-lg leading-relaxed text-muted-foreground">
                            DevCollab exists to make project management easier to use while working on projects. No more wrestling with bloated tools that slow you down — just a clean, fast workspace that gets out of your way.
                        </p>
                        <p class="mt-4 text-lg leading-relaxed text-muted-foreground">
                            Built by a developer who wanted something better, DevCollab brings the best ideas together and automates the tedious parts so your team can focus on what matters: shipping great software.
                        </p>
                    </div>
                    <div
                        data-animate="mission-visual"
                        class="transition-all duration-700"
                        :class="isVisible('mission-visual') ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'"
                    >
                        <div class="overflow-hidden rounded-2xl border border-border/50 bg-card p-8 shadow-xl">
                            <div class="grid grid-cols-2 gap-6 text-center">
                                <div>
                                    <div class="text-4xl font-extrabold tracking-tight text-primary">10K+</div>
                                    <div class="mt-1 text-sm text-muted-foreground">Teams</div>
                                </div>
                                <div>
                                    <div class="text-4xl font-extrabold tracking-tight text-primary">2M+</div>
                                    <div class="mt-1 text-sm text-muted-foreground">Tasks Managed</div>
                                </div>
                                <div>
                                    <div class="text-4xl font-extrabold tracking-tight text-primary">99.9%</div>
                                    <div class="mt-1 text-sm text-muted-foreground">Uptime</div>
                                </div>
                                <div>
                                    <div class="text-4xl font-extrabold tracking-tight text-primary">4.9/5</div>
                                    <div class="mt-1 text-sm text-muted-foreground">Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="values-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('values-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        Our values
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        The principles that guide everything we build.
                    </p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="(value, index) in values"
                        :key="value.title"
                        :data-animate="`value-${index}`"
                        class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card p-6 transition-all duration-500 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg"
                        :class="isVisible(`value-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br text-white" :class="value.color">
                            <component :is="value.icon" class="h-6 w-6" />
                        </div>
                        <h3 class="mb-2 text-lg font-semibold">{{ value.title }}</h3>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ value.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section class="bg-muted/30 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="team-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('team-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        Meet the developer
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        The person behind DevCollab.
                    </p>
                </div>
                <div class="mx-auto max-w-md">
                    <div
                        data-animate="team-0"
                        class="flex flex-col items-center rounded-2xl border border-border/50 bg-card p-8 text-center transition-all duration-500 hover:border-primary/20 hover:shadow-lg"
                        :class="isVisible('team-0') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br text-2xl font-bold text-white"
                            :class="team[0].color"
                        >
                            {{ team[0].avatar }}
                        </div>
                        <div class="mt-4 text-xl font-semibold">{{ team[0].name }}</div>
                        <div class="mt-1 text-sm text-primary">{{ team[0].role }}</div>
                        <p class="mt-4 text-sm leading-relaxed text-muted-foreground">{{ team[0].bio }}</p>
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
                    Join thousands of teams
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    Start managing your projects with DevCollab today. Free to start, no credit card required.
                </p>
                <div class="mt-10">
                    <Button size="lg" as-child class="h-12 bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30">
                        <Link :href="register()">
                            Get Started Free
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Bug Reports & Improvements -->
        <section class="border-t border-border/50 bg-muted/30 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="feedback-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('feedback-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        Help us improve
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Found a bug or have an idea to make DevCollab better? We'd love to hear from you.
                    </p>
                </div>
                <div
                    data-animate="feedback-cards"
                    class="mx-auto grid max-w-3xl gap-6 sm:grid-cols-2 transition-all duration-700"
                    :class="isVisible('feedback-cards') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <!-- Bug Report -->
                    <Link
                        href="/contact"
                        class="group flex flex-col items-center rounded-2xl border border-border/50 bg-card p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:border-rose-500/30 hover:shadow-lg hover:shadow-rose-500/5"
                    >
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-rose-100 text-rose-600 transition-colors group-hover:bg-rose-500 group-hover:text-white dark:bg-rose-950/50 dark:text-rose-400">
                            <Bug class="h-7 w-7" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold">Report a Bug</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                            Something not working right? Let us know and we'll fix it as soon as possible.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary">
                            Report bug <ArrowRight class="h-4 w-4" />
                        </span>
                    </Link>

                    <!-- Suggest Improvement -->
                    <Link
                        href="/contact"
                        class="group flex flex-col items-center rounded-2xl border border-border/50 bg-card p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/5"
                    >
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 transition-colors group-hover:bg-indigo-500 group-hover:text-white dark:bg-indigo-950/50 dark:text-indigo-400">
                            <Lightbulb class="h-7 w-7" />
                        </div>
                        <h3 class="mt-4 text-lg font-semibold">Suggest an Improvement</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                            Have an idea to make DevCollab better? We're always looking for ways to improve.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary">
                            Share idea <ArrowRight class="h-4 w-4" />
                        </span>
                    </Link>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
