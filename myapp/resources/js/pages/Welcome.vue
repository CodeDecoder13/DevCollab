<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import {
    Activity,
    Bell,
    CheckSquare,
    ChevronRight,
    FolderKanban,
    Menu,
    MessageSquare,
    Users,
    Check,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const { y } = useWindowScroll();
const isScrolled = computed(() => y.value > 50);
const mobileMenuOpen = ref(false);

function scrollTo(id: string) {
    mobileMenuOpen.value = false;
    const el = document.querySelector(id);
    el?.scrollIntoView({ behavior: 'smooth' });
}

// Scroll animation observer
const visibleSections = ref<Set<string>>(new Set());
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    visibleSections.value.add(entry.target.id || entry.target.getAttribute('data-animate') || '');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 },
    );

    document.querySelectorAll('[data-animate]').forEach((el) => {
        observer?.observe(el);
    });
});

onUnmounted(() => {
    observer?.disconnect();
});

function isVisible(key: string) {
    return visibleSections.value.has(key);
}

const features = [
    {
        icon: FolderKanban,
        title: 'Kanban Boards',
        description: 'Visualize your workflow with drag-and-drop kanban boards',
        color: 'bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400',
    },
    {
        icon: CheckSquare,
        title: 'Task Management',
        description: 'Create, assign, and track tasks with priorities and due dates',
        color: 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400',
    },
    {
        icon: Users,
        title: 'Team Collaboration',
        description: 'Add team members, assign roles, and work together seamlessly',
        color: 'bg-violet-100 text-violet-600 dark:bg-violet-950 dark:text-violet-400',
    },
    {
        icon: MessageSquare,
        title: 'Comments & Attachments',
        description: 'Discuss tasks and share files directly in context',
        color: 'bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400',
    },
    {
        icon: Activity,
        title: 'Activity Tracking',
        description: 'See what happened across all your projects at a glance',
        color: 'bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400',
    },
    {
        icon: Bell,
        title: 'Real-time Notifications',
        description: 'Stay updated with instant notifications on changes',
        color: 'bg-cyan-100 text-cyan-600 dark:bg-cyan-950 dark:text-cyan-400',
    },
];

const pricingPlans = [
    {
        name: 'Free',
        price: '$0',
        period: '/mo',
        description: 'For individuals',
        features: ['Up to 3 projects', '2 team members', 'Basic task management', 'Activity log'],
        highlighted: false,
        cta: 'Get Started',
        ctaVariant: 'outline' as const,
    },
    {
        name: 'Pro',
        price: '$12',
        period: '/mo',
        description: 'For growing teams',
        features: ['Unlimited projects', '10 team members', 'Labels & subtasks', 'Priority support', 'File attachments'],
        highlighted: true,
        cta: 'Start Free Trial',
        ctaVariant: 'default' as const,
    },
    {
        name: 'Enterprise',
        price: '$39',
        period: '/mo',
        description: 'For organizations',
        features: [
            'Everything in Pro',
            'Unlimited members',
            'Advanced analytics',
            'Custom integrations',
            'Dedicated support',
        ],
        highlighted: false,
        cta: 'Contact Sales',
        ctaVariant: 'outline' as const,
    },
];

const navLinks = [
    { label: 'Features', href: '#features' },
    { label: 'About', href: '#about' },
    { label: 'Pricing', href: '#pricing' },
];
</script>

<template>
    <Head title="DevCollab — Collaborate. Organize. Deliver.">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-background text-foreground" style="scroll-behavior: smooth">
        <!-- Sticky Navigation -->
        <nav
            class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
            :class="
                isScrolled
                    ? 'border-b bg-background/80 shadow-sm backdrop-blur-sm'
                    : 'bg-transparent'
            "
        >
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <Link href="/" class="flex items-center gap-2">
                    <AppLogoIcon class="h-7 w-7 text-primary" />
                    <span class="text-lg font-bold">DevCollab</span>
                </Link>

                <!-- Desktop Nav -->
                <div class="hidden items-center gap-6 md:flex">
                    <button
                        v-for="link in navLinks"
                        :key="link.href"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                        @click="scrollTo(link.href)"
                    >
                        {{ link.label }}
                    </button>
                </div>

                <div class="hidden items-center gap-3 md:flex">
                    <ThemeToggle />
                    <template v-if="$page.props.auth.user">
                        <Button as-child>
                            <Link :href="dashboard()">Dashboard</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button variant="ghost" as-child>
                            <Link :href="login()">Log in</Link>
                        </Button>
                        <Button v-if="canRegister" as-child>
                            <Link :href="register()">Register</Link>
                        </Button>
                    </template>
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex items-center gap-1 md:hidden">
                    <ThemeToggle />
                    <Sheet v-model:open="mobileMenuOpen">
                        <SheetTrigger as-child>
                            <Button variant="ghost" size="sm">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                    <SheetContent side="right" class="w-72">
                        <SheetHeader>
                            <SheetTitle>Menu</SheetTitle>
                        </SheetHeader>
                        <div class="mt-6 flex flex-col gap-4">
                            <button
                                v-for="link in navLinks"
                                :key="link.href"
                                class="text-left text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                                @click="scrollTo(link.href)"
                            >
                                {{ link.label }}
                            </button>
                            <hr class="my-2" />
                            <template v-if="$page.props.auth.user">
                                <Button as-child class="w-full">
                                    <Link :href="dashboard()">Dashboard</Link>
                                </Button>
                            </template>
                            <template v-else>
                                <Button variant="outline" as-child class="w-full">
                                    <Link :href="login()">Log in</Link>
                                </Button>
                                <Button v-if="canRegister" as-child class="w-full">
                                    <Link :href="register()">Register</Link>
                                </Button>
                            </template>
                        </div>
                    </SheetContent>
                    </Sheet>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative flex min-h-screen items-center justify-center overflow-hidden pt-16">
            <!-- Background -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,hsl(var(--muted))_1px,transparent_1px),linear-gradient(to_bottom,hsl(var(--muted))_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-40" />
                <div class="absolute left-1/2 top-0 -z-10 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-gradient-to-br from-primary/20 via-primary/5 to-transparent blur-3xl" />
            </div>

            <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
                <div
                    class="transition-all duration-700"
                    :class="true ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                >
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border bg-background/80 px-4 py-1.5 text-sm backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500" />
                        Now with Kanban boards & real-time notifications
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl lg:text-7xl">
                        Collaborate.
                        <span class="bg-gradient-to-r from-primary to-primary/60 bg-clip-text text-transparent">
                            Organize.
                        </span>
                        Deliver.
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground sm:text-xl">
                        The modern project management platform for teams that ship.
                        Track tasks, manage sprints, and collaborate in real-time.
                    </p>
                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Button size="lg" as-child class="w-full sm:w-auto">
                            <Link :href="register()">
                                Get Started Free
                                <ChevronRight class="ml-1 h-4 w-4" />
                            </Link>
                        </Button>
                        <Button
                            size="lg"
                            variant="outline"
                            class="w-full sm:w-auto"
                            @click="scrollTo('#features')"
                        >
                            See How It Works
                        </Button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div
                    data-animate="features-header"
                    class="mb-16 text-center transition-all duration-700"
                    :class="isVisible('features-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                        Everything your team needs
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Powerful features to help your team collaborate, organize, and deliver projects on time.
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.title"
                        :data-animate="`feature-${index}`"
                        class="group rounded-xl border bg-card p-6 transition-all duration-500 hover:-translate-y-1 hover:shadow-md"
                        :class="isVisible(`feature-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg"
                            :class="feature.color"
                        >
                            <component :is="feature.icon" class="h-6 w-6" />
                        </div>
                        <h3 class="mb-2 text-lg font-semibold">{{ feature.title }}</h3>
                        <p class="text-sm text-muted-foreground">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="bg-muted/30 py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div
                        data-animate="about-text"
                        class="transition-all duration-700"
                        :class="isVisible('about-text') ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                    >
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                            Built for modern teams
                        </h2>
                        <p class="mt-4 text-muted-foreground">
                            DevCollab was designed from the ground up to help development teams
                            work more efficiently. No more scattered spreadsheets, lost emails,
                            or forgotten tasks.
                        </p>
                        <p class="mt-4 text-muted-foreground">
                            With intuitive kanban boards, smart task management, and real-time
                            collaboration tools, your team can focus on what matters most —
                            shipping great software.
                        </p>
                        <ul class="mt-6 space-y-3">
                            <li class="flex items-center gap-3 text-sm">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10">
                                    <Check class="h-3.5 w-3.5 text-primary" />
                                </div>
                                Intuitive drag-and-drop interface
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10">
                                    <Check class="h-3.5 w-3.5 text-primary" />
                                </div>
                                Role-based access control
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10">
                                    <Check class="h-3.5 w-3.5 text-primary" />
                                </div>
                                Activity logging and audit trail
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10">
                                    <Check class="h-3.5 w-3.5 text-primary" />
                                </div>
                                File attachments and comments
                            </li>
                        </ul>
                    </div>

                    <!-- Dashboard Mockup -->
                    <div
                        data-animate="about-mockup"
                        class="transition-all duration-700"
                        :class="isVisible('about-mockup') ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'"
                    >
                        <div class="overflow-hidden rounded-xl border bg-card shadow-xl">
                            <div class="flex items-center gap-2 border-b bg-muted/50 px-4 py-3">
                                <div class="h-3 w-3 rounded-full bg-red-400" />
                                <div class="h-3 w-3 rounded-full bg-yellow-400" />
                                <div class="h-3 w-3 rounded-full bg-green-400" />
                                <span class="ml-2 text-xs text-muted-foreground">DevCollab Dashboard</span>
                            </div>
                            <div class="p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <div>
                                        <div class="h-4 w-32 rounded bg-muted" />
                                        <div class="mt-2 h-3 w-48 rounded bg-muted/60" />
                                    </div>
                                    <div class="h-8 w-24 rounded-md bg-primary/20" />
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-lg border p-3">
                                        <div class="text-xs text-muted-foreground">To Do</div>
                                        <div class="mt-1 text-xl font-bold">12</div>
                                    </div>
                                    <div class="rounded-lg border p-3">
                                        <div class="text-xs text-muted-foreground">In Progress</div>
                                        <div class="mt-1 text-xl font-bold text-amber-500">5</div>
                                    </div>
                                    <div class="rounded-lg border p-3">
                                        <div class="text-xs text-muted-foreground">Completed</div>
                                        <div class="mt-1 text-xl font-bold text-emerald-500">28</div>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3 rounded-md border p-2">
                                        <div class="h-3 w-3 rounded-sm bg-amber-400" />
                                        <div class="h-3 flex-1 rounded bg-muted/60" />
                                        <div class="h-5 w-5 rounded-full bg-muted" />
                                    </div>
                                    <div class="flex items-center gap-3 rounded-md border p-2">
                                        <div class="h-3 w-3 rounded-sm bg-blue-400" />
                                        <div class="h-3 w-3/4 rounded bg-muted/60" />
                                        <div class="h-5 w-5 rounded-full bg-muted" />
                                    </div>
                                    <div class="flex items-center gap-3 rounded-md border p-2">
                                        <div class="h-3 w-3 rounded-sm bg-emerald-400" />
                                        <div class="h-3 w-2/3 rounded bg-muted/60" />
                                        <div class="h-5 w-5 rounded-full bg-muted" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div
                    data-animate="pricing-header"
                    class="mb-16 text-center transition-all duration-700"
                    :class="isVisible('pricing-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                        Simple, transparent pricing
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Choose the plan that fits your team. No hidden fees.
                    </p>
                </div>

                <div class="grid gap-8 lg:grid-cols-3">
                    <Card
                        v-for="(plan, index) in pricingPlans"
                        :key="plan.name"
                        :data-animate="`pricing-${index}`"
                        class="relative flex flex-col transition-all duration-500"
                        :class="[
                            isVisible(`pricing-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
                            plan.highlighted
                                ? 'border-primary shadow-lg lg:scale-105'
                                : 'hover:shadow-md',
                        ]"
                        :style="{ transitionDelay: `${index * 150}ms` }"
                    >
                        <div
                            v-if="plan.highlighted"
                            class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-primary px-3 py-0.5 text-xs font-medium text-primary-foreground"
                        >
                            Most Popular
                        </div>
                        <CardHeader class="text-center">
                            <CardTitle class="text-lg">{{ plan.name }}</CardTitle>
                            <div class="mt-2">
                                <span class="text-4xl font-extrabold">{{ plan.price }}</span>
                                <span class="text-muted-foreground">{{ plan.period }}</span>
                            </div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ plan.description }}
                            </p>
                        </CardHeader>
                        <CardContent class="flex-1">
                            <ul class="space-y-3">
                                <li
                                    v-for="feat in plan.features"
                                    :key="feat"
                                    class="flex items-center gap-2 text-sm"
                                >
                                    <Check class="h-4 w-4 shrink-0 text-primary" />
                                    {{ feat }}
                                </li>
                            </ul>
                        </CardContent>
                        <CardFooter>
                            <Button
                                :variant="plan.ctaVariant"
                                as-child
                                class="w-full"
                            >
                                <Link :href="register()">{{ plan.cta }}</Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t bg-muted/30 py-12">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div class="flex items-center gap-2">
                        <AppLogoIcon class="h-6 w-6 text-primary" />
                        <span class="font-semibold">DevCollab</span>
                        <span class="text-sm text-muted-foreground">— Project management for teams that ship</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-muted-foreground">
                        <button class="transition-colors hover:text-foreground" @click="scrollTo('#features')">Features</button>
                        <button class="transition-colors hover:text-foreground" @click="scrollTo('#about')">About</button>
                        <button class="transition-colors hover:text-foreground" @click="scrollTo('#pricing')">Pricing</button>
                        <Link :href="login()" class="transition-colors hover:text-foreground">Login</Link>
                        <Link v-if="canRegister" :href="register()" class="transition-colors hover:text-foreground">Register</Link>
                    </div>
                </div>
                <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t pt-8 text-sm text-muted-foreground md:flex-row">
                    <p>Built with Laravel, Vue, and Tailwind CSS</p>
                    <p>&copy; {{ new Date().getFullYear() }} DevCollab. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
