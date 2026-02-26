<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BookOpen,
    ChevronDown,
    FolderOpen,
    ListChecks,
    UserCheck,
    UserPlus,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
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

const steps = [
    { icon: UserPlus, number: '1', title: 'Create your account', description: 'Sign up in seconds with your email. No credit card required to get started.', color: 'from-indigo-500 to-violet-500' },
    { icon: FolderOpen, number: '2', title: 'Create a project', description: 'Set up your first project with a name, description, and choose your workflow style.', color: 'from-emerald-500 to-teal-500' },
    { icon: UserCheck, number: '3', title: 'Invite your team', description: 'Add team members by email and assign roles. Everyone gets notified instantly.', color: 'from-amber-500 to-orange-500' },
    { icon: ListChecks, number: '4', title: 'Start tracking', description: 'Create tasks, organize your board, and start shipping. It\'s that simple.', color: 'from-rose-500 to-pink-500' },
];

const faqs = [
    { question: 'How do I create my first project?', answer: 'After signing up, click "New Project" from your dashboard. Give it a name, add an optional description, and you\'re ready to go. You can customize columns and invite team members right away.' },
    { question: 'Can I import tasks from other tools?', answer: 'Yes! DevCollab supports importing from CSV files, making it easy to migrate from spreadsheets or other project management tools. We\'re also working on direct integrations with popular tools.' },
    { question: 'How do roles and permissions work?', answer: 'Each project has three roles: Owner (full control), Admin (manage members and settings), and Member (create and manage tasks). You can assign roles when inviting team members.' },
    { question: 'Is there a limit on the free plan?', answer: 'The free plan includes up to 3 projects and 2 team members per project. You get full access to kanban boards, task management, and activity tracking. Upgrade to Pro for unlimited projects and members.' },
    { question: 'How does the kanban board work?', answer: 'Your kanban board shows tasks organized in columns (e.g., Backlog, To Do, In Progress, Done). Drag and drop tasks between columns to update their status. You can customize column names and add as many columns as you need.' },
    { question: 'Can I use DevCollab for non-software projects?', answer: 'Absolutely! While DevCollab is optimized for software development workflows, its flexible task management and collaboration features work great for any team-based project.' },
];
</script>

<template>
    <Head title="Guide — DevCollab" />

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
                    <BookOpen class="h-3.5 w-3.5" />
                    Guide
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing: -0.02em">
                    Get Started with
                    <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">DevCollab</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground" style="line-height: 1.7">
                    Everything you need to know to set up your workspace and start managing projects like a pro.
                </p>
            </div>
        </section>

        <!-- Getting Started Steps -->
        <section class="pb-24 sm:pb-32">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="steps-header"
                    class="mb-16 text-center transition-all duration-700"
                    :class="isVisible('steps-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        4 steps to get going
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">Set up your workspace in under 5 minutes.</p>
                </div>

                <div class="relative">
                    <!-- Connecting line -->
                    <div class="absolute left-8 top-0 hidden h-full w-px bg-gradient-to-b from-indigo-500/50 via-violet-500/50 to-rose-500/50 sm:block" />

                    <div class="space-y-8">
                        <div
                            v-for="(step, index) in steps"
                            :key="step.number"
                            :data-animate="`step-${index}`"
                            class="relative flex gap-6 transition-all duration-500"
                            :class="isVisible(`step-${index}`) ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                            :style="{ transitionDelay: `${index * 150}ms` }"
                        >
                            <!-- Step number -->
                            <div class="relative z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-2xl font-bold text-white shadow-lg" :class="step.color">
                                {{ step.number }}
                            </div>
                            <!-- Content -->
                            <div class="flex-1 rounded-2xl border border-border/50 bg-card p-6 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <component :is="step.icon" class="h-5 w-5 text-primary" />
                                    <h3 class="text-lg font-semibold">{{ step.title }}</h3>
                                </div>
                                <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ step.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="bg-muted/30 py-24 sm:py-32">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="faq-header"
                    class="mb-16 text-center transition-all duration-700"
                    :class="isVisible('faq-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="letter-spacing: -0.02em">
                        Frequently asked questions
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">Everything you need to know about DevCollab.</p>
                </div>

                <div
                    data-animate="faq-list"
                    class="space-y-3 transition-all duration-700"
                    :class="isVisible('faq-list') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <Collapsible
                        v-for="(faq, index) in faqs"
                        :key="index"
                        class="overflow-hidden rounded-xl border border-border/50 bg-card"
                    >
                        <CollapsibleTrigger class="flex w-full items-center justify-between px-6 py-4 text-left text-sm font-medium transition-colors hover:bg-accent/50">
                            {{ faq.question }}
                            <ChevronDown class="h-4 w-4 shrink-0 text-muted-foreground transition-transform duration-200 [[data-state=open]>&]:rotate-180" />
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <div class="border-t border-border/50 px-6 py-4 text-sm leading-relaxed text-muted-foreground">
                                {{ faq.answer }}
                            </div>
                        </CollapsibleContent>
                    </Collapsible>
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
                    Start your first project
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    Ready to streamline your workflow? Create your free account and start building today.
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
    </MarketingLayout>
</template>
