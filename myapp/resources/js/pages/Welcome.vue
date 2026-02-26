<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import {
    Activity,
    ArrowRight,
    Bell,
    CheckSquare,
    ChevronRight,
    FolderKanban,
    GitBranch,
    Layers,
    Menu,
    MessageSquare,
    Quote,
    Rocket,
    Shield,
    Star,
    Users,
    Zap,
    Check,
    BarChart3,
    Globe,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch, nextTick } from 'vue';
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

// Hero background video carousel
const heroVideos = ['/herosection/vid-1.mp4', '/herosection/vid-2.mp4', '/herosection/vid-3.mp4'];
const activeVideoIndex = ref(0);
const nextVideoIndex = ref(1);
const isTransitioning = ref(false);
const videoRefA = ref<HTMLVideoElement | null>(null);
const videoRefB = ref<HTMLVideoElement | null>(null);
const showA = ref(true); // which slot is currently visible
let videoTimer: ReturnType<typeof setTimeout> | null = null;

function scheduleNextVideo() {
    if (videoTimer) clearTimeout(videoTimer);
    videoTimer = setTimeout(() => {
        transitionToNext();
    }, 8000); // switch every 8 seconds
}

function transitionToNext() {
    const next = (activeVideoIndex.value + 1) % heroVideos.length;
    nextVideoIndex.value = next;

    // Preload the incoming video into the hidden slot
    const incomingEl = showA.value ? videoRefB.value : videoRefA.value;
    if (incomingEl) {
        incomingEl.src = heroVideos[next];
        incomingEl.load();
        incomingEl.play().catch(() => {});
    }

    // Brief delay to let the incoming video buffer a frame, then crossfade
    setTimeout(() => {
        isTransitioning.value = true;
        showA.value = !showA.value;
        activeVideoIndex.value = next;

        // Reset transition flag after the CSS transition completes
        setTimeout(() => {
            isTransitioning.value = false;
            scheduleNextVideo();
        }, 1200); // matches CSS transition duration
    }, 300);
}

function onVideoReady() {
    scheduleNextVideo();
}

function jumpToVideo(index: number) {
    if (index === activeVideoIndex.value || isTransitioning.value) return;
    if (videoTimer) clearTimeout(videoTimer);
    nextVideoIndex.value = index;

    const incomingEl = showA.value ? videoRefB.value : videoRefA.value;
    if (incomingEl) {
        incomingEl.src = heroVideos[index];
        incomingEl.load();
        incomingEl.play().catch(() => {});
    }

    setTimeout(() => {
        isTransitioning.value = true;
        showA.value = !showA.value;
        activeVideoIndex.value = index;
        setTimeout(() => {
            isTransitioning.value = false;
            scheduleNextVideo();
        }, 1200);
    }, 300);
}

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
    if (videoTimer) clearTimeout(videoTimer);
});

function isVisible(key: string) {
    return visibleSections.value.has(key);
}

const features = [
    {
        icon: FolderKanban,
        title: 'Kanban Boards',
        description: 'Visualize your workflow with intuitive drag-and-drop kanban boards that adapt to your process.',
        iconColor: 'text-indigo-600 dark:text-indigo-400',
        bgLight: 'bg-indigo-100 dark:bg-indigo-950/50',
        glowColor: 'from-indigo-500 to-blue-500',
    },
    {
        icon: CheckSquare,
        title: 'Task Management',
        description: 'Create, assign, and track tasks with priorities, due dates, labels, and subtasks.',
        iconColor: 'text-emerald-600 dark:text-emerald-400',
        bgLight: 'bg-emerald-100 dark:bg-emerald-950/50',
        glowColor: 'from-emerald-500 to-teal-500',
    },
    {
        icon: Users,
        title: 'Team Collaboration',
        description: 'Invite team members, assign roles, and work together seamlessly across projects.',
        iconColor: 'text-violet-600 dark:text-violet-400',
        bgLight: 'bg-violet-100 dark:bg-violet-950/50',
        glowColor: 'from-violet-500 to-purple-500',
    },
    {
        icon: MessageSquare,
        title: 'Comments & Attachments',
        description: 'Discuss tasks in context, share files, and keep all communication organized.',
        iconColor: 'text-amber-600 dark:text-amber-400',
        bgLight: 'bg-amber-100 dark:bg-amber-950/50',
        glowColor: 'from-amber-500 to-orange-500',
    },
    {
        icon: Activity,
        title: 'Activity Tracking',
        description: 'Full audit trail of every change across all your projects at a glance.',
        iconColor: 'text-rose-600 dark:text-rose-400',
        bgLight: 'bg-rose-100 dark:bg-rose-950/50',
        glowColor: 'from-rose-500 to-pink-500',
    },
    {
        icon: Bell,
        title: 'Real-time Notifications',
        description: 'Stay in the loop with instant notifications when things change.',
        iconColor: 'text-cyan-600 dark:text-cyan-400',
        bgLight: 'bg-cyan-100 dark:bg-cyan-950/50',
        glowColor: 'from-cyan-500 to-sky-500',
    },
];

const testimonials = [
    {
        name: 'Sarah Chen',
        role: 'Engineering Lead',
        company: 'TechFlow',
        avatar: 'SC',
        avatarColor: 'from-indigo-500 to-violet-500',
        quote: 'DevCollab replaced three tools we were using. The kanban boards and real-time updates have cut our sprint planning time in half.',
        rating: 5,
    },
    {
        name: 'Marcus Rivera',
        role: 'Product Manager',
        company: 'CloudScale',
        avatar: 'MR',
        avatarColor: 'from-emerald-500 to-teal-500',
        quote: 'Finally, a project management tool that developers actually want to use. The interface is clean, fast, and stays out of our way.',
        rating: 5,
    },
    {
        name: 'Emily Zhang',
        role: 'CTO',
        company: 'DataPulse',
        avatar: 'EZ',
        avatarColor: 'from-amber-500 to-orange-500',
        quote: 'We migrated from Jira in a weekend. DevCollab gives us everything we need without the bloat. Our team velocity went up 30%.',
        rating: 5,
    },
];

const stats = [
    { value: '10K+', label: 'Teams', icon: Users },
    { value: '2M+', label: 'Tasks Managed', icon: CheckSquare },
    { value: '99.9%', label: 'Uptime', icon: Shield },
    { value: '4.9/5', label: 'User Rating', icon: Star },
];

const pricingPlans = [
    {
        name: 'Free',
        price: '$0',
        period: '/mo',
        description: 'For individuals and small side projects',
        features: ['Up to 3 projects', '2 team members', 'Basic task management', 'Activity log'],
        highlighted: false,
        cta: 'Get Started',
        ctaVariant: 'outline' as const,
    },
    {
        name: 'Pro',
        price: '$12',
        period: '/mo',
        description: 'For growing teams that need more power',
        features: ['Unlimited projects', '10 team members', 'Labels & subtasks', 'Priority support', 'File attachments'],
        highlighted: true,
        cta: 'Start Free Trial',
        ctaVariant: 'default' as const,
    },
    {
        name: 'Enterprise',
        price: '$39',
        period: '/mo',
        description: 'For organizations at scale',
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
    { label: 'Testimonials', href: '#testimonials' },
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
                    ? 'border-b border-border/50 bg-background/80 shadow-sm backdrop-blur-xl'
                    : 'bg-transparent'
            "
        >
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-2">
                    <AppLogoIcon class="h-8 w-8" />
                    <span class="text-lg font-bold tracking-tight transition-colors" :class="isScrolled ? 'text-foreground' : 'text-white'">DevCollab</span>
                </Link>

                <!-- Desktop Nav -->
                <div class="hidden items-center gap-1 md:flex">
                    <button
                        v-for="link in navLinks"
                        :key="link.href"
                        class="rounded-lg px-3.5 py-2 text-sm font-medium transition-colors"
                        :class="isScrolled ? 'text-muted-foreground hover:bg-accent hover:text-foreground' : 'text-white/70 hover:text-white hover:bg-white/10'"
                        @click="scrollTo(link.href)"
                    >
                        {{ link.label }}
                    </button>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    <ThemeToggle />
                    <template v-if="$page.props.auth.user">
                        <Button as-child>
                            <Link :href="dashboard()">
                                Dashboard
                                <ArrowRight class="ml-1.5 h-4 w-4" />
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button variant="ghost" as-child :class="isScrolled ? '' : 'text-white/80 hover:text-white hover:bg-white/10'">
                            <Link :href="login()">Log in</Link>
                        </Button>
                        <Button v-if="canRegister" as-child class="bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/30 border-0">
                            <Link :href="register()">
                                Get Started
                                <ArrowRight class="ml-1.5 h-4 w-4" />
                            </Link>
                        </Button>
                    </template>
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex items-center gap-1 md:hidden">
                    <ThemeToggle />
                    <Sheet v-model:open="mobileMenuOpen">
                        <SheetTrigger as-child>
                            <Button variant="ghost" size="sm" :class="isScrolled ? '' : 'text-white hover:bg-white/10'">
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
                            <hr class="my-2 border-border" />
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
                                    <Link :href="register()">Get Started</Link>
                                </Button>
                            </template>
                        </div>
                    </SheetContent>
                    </Sheet>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative flex min-h-[100dvh] items-center justify-center overflow-hidden bg-[#09090b] pt-16">
            <!-- Video Background with Crossfade -->
            <div class="hero-video-bg absolute inset-0 z-0">
                <!-- Video Slot A -->
                <video
                    ref="videoRefA"
                    :src="heroVideos[0]"
                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[1200ms] ease-in-out"
                    :class="showA ? 'opacity-100' : 'opacity-0'"
                    autoplay
                    muted
                    loop
                    playsinline
                    @canplay="onVideoReady"
                />
                <!-- Video Slot B -->
                <video
                    ref="videoRefB"
                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[1200ms] ease-in-out"
                    :class="showA ? 'opacity-0' : 'opacity-100'"
                    muted
                    playsinline
                />
            </div>

            <!-- Overlays (above video, below content) -->
            <div class="absolute inset-0 z-[1]">
                <!-- Dark overlay for readability -->
                <div class="absolute inset-0 bg-black/55" />

                <!-- Bottom fade into page background -->
                <div class="absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-background to-transparent" />

                <!-- Top subtle fade for nav blending -->
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/30 to-transparent" />

                <!-- Accent gradient glow -->
                <div class="absolute left-1/2 top-0 h-[600px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-500/15 via-violet-500/10 to-transparent blur-3xl" />
            </div>

            <!-- Video progress indicators -->
            <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2 sm:bottom-10">
                <button
                    v-for="(_, index) in heroVideos"
                    :key="index"
                    class="group relative h-1.5 overflow-hidden rounded-full transition-all duration-300 focus:outline-none"
                    :class="activeVideoIndex === index ? 'w-10 bg-white/30' : 'w-5 bg-white/15 hover:bg-white/25'"
                    @click="jumpToVideo(index)"
                >
                    <div
                        class="absolute inset-y-0 left-0 rounded-full bg-white"
                        :class="activeVideoIndex === index ? 'w-full transition-all duration-[8000ms] ease-linear' : 'w-0 transition-none'"
                    />
                </button>
            </div>

            <div class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6">
                <div
                    class="transition-all duration-700"
                    :class="true ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                >
                    <!-- Badge -->
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-white backdrop-blur-md sm:mb-8 sm:px-4 sm:text-sm">
                        <Zap class="h-3.5 w-3.5 shrink-0" />
                        <span class="sm:inline">Now with Kanban boards & real-time collaboration</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl" style="letter-spacing: -0.02em">
                        Ship faster,
                        <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">
                            together.
                        </span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-white/70 sm:text-xl" style="line-height: 1.7">
                        The modern project management platform built for teams that ship.
                        Track tasks, manage sprints, and collaborate — all in one place.
                    </p>

                    <!-- CTAs -->
                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Button size="lg" as-child class="h-12 w-full bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 transition-all hover:shadow-xl hover:shadow-indigo-500/30 sm:w-auto">
                            <Link :href="register()">
                                Start Building Free
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Link>
                        </Button>
                        <Button
                            size="lg"
                            variant="outline"
                            class="h-12 w-full border-white/20 bg-white/10 px-8 text-base text-white backdrop-blur-sm hover:bg-white/20 hover:text-white sm:w-auto"
                            @click="scrollTo('#features')"
                        >
                            See How It Works
                        </Button>
                    </div>

                    <!-- Social proof snippet -->
                    <div class="mt-10 flex flex-col items-center justify-center gap-3 text-sm text-white/60 sm:mt-12 sm:flex-row sm:gap-4">
                        <div class="flex -space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-xs font-semibold text-white ring-2 ring-white/20">JK</div>
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 text-xs font-semibold text-white ring-2 ring-white/20">AL</div>
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-xs font-semibold text-white ring-2 ring-white/20">RW</div>
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-pink-500 text-xs font-semibold text-white ring-2 ring-white/20">+</div>
                        </div>
                        <span>Trusted by <strong class="text-white">10,000+</strong> teams worldwide</span>
                    </div>
                </div>

                <!-- Product Preview -->
                <div
                    data-animate="hero-preview"
                    class="relative z-10 mx-auto mt-12 max-w-4xl transition-all duration-1000 sm:mt-16"
                    :class="isVisible('hero-preview') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
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
                        <!-- Dashboard mockup -->
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
                            <!-- Kanban columns -->
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
                                        <div class="h-2.5 w-4/5 rounded bg-foreground/10 line-through" />
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
            </div>
        </section>

        <!-- Stats Section -->
        <section class="relative border-y border-border/50 bg-muted/30 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                    <div
                        v-for="(stat, index) in stats"
                        :key="stat.label"
                        :data-animate="`stat-${index}`"
                        class="text-center transition-all duration-500"
                        :class="isVisible(`stat-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <component :is="stat.icon" class="mx-auto mb-3 h-5 w-5 text-primary" />
                        <div class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ stat.value }}</div>
                        <div class="mt-1 text-sm text-muted-foreground">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="features-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('features-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                        <Layers class="h-3.5 w-3.5" />
                        Features
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                        Everything your team needs
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Powerful features designed to help your team collaborate, organize, and deliver projects on time.
                    </p>
                </div>

                <!-- Bento Grid -->
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.title"
                        :data-animate="`feature-${index}`"
                        class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card p-6 transition-all duration-500 hover:-translate-y-1 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :class="isVisible(`feature-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 100}ms` }"
                    >
                        <!-- Gradient glow on hover -->
                        <div
                            class="absolute -right-12 -top-12 h-32 w-32 rounded-full opacity-0 blur-3xl transition-opacity duration-500 group-hover:opacity-100"
                            :class="`bg-gradient-to-br ${feature.glowColor}`"
                        />
                        <div
                            class="relative mb-4 flex h-12 w-12 items-center justify-center rounded-xl"
                            :class="feature.bgLight"
                        >
                            <component
                                :is="feature.icon"
                                class="h-6 w-6"
                                :class="feature.iconColor"
                            />
                        </div>
                        <h3 class="relative mb-2 text-lg font-semibold">{{ feature.title }}</h3>
                        <p class="relative text-sm leading-relaxed text-muted-foreground">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About / How It Works Section -->
        <section id="about" class="relative overflow-hidden bg-muted/30 py-24 sm:py-32">
            <!-- Background decoration -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute right-0 top-1/4 h-[500px] w-[500px] rounded-full bg-gradient-to-l from-indigo-500/5 to-transparent blur-3xl" />
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                    <div
                        data-animate="about-text"
                        class="transition-all duration-700"
                        :class="isVisible('about-text') ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-8'"
                    >
                        <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                            <Rocket class="h-3.5 w-3.5" />
                            Why DevCollab
                        </div>
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                            Built for modern
                            <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">dev teams</span>
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-muted-foreground">
                            DevCollab was designed from the ground up to help development teams
                            work more efficiently. No more scattered spreadsheets, lost emails,
                            or forgotten tasks.
                        </p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                    <Check class="h-3.5 w-3.5 text-white" />
                                </div>
                                <div>
                                    <span class="font-medium">Intuitive drag-and-drop</span>
                                    <span class="text-muted-foreground"> — Move tasks between columns with ease</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                    <Check class="h-3.5 w-3.5 text-white" />
                                </div>
                                <div>
                                    <span class="font-medium">Role-based access control</span>
                                    <span class="text-muted-foreground"> — Fine-grained permissions for every team</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                    <Check class="h-3.5 w-3.5 text-white" />
                                </div>
                                <div>
                                    <span class="font-medium">Full activity audit trail</span>
                                    <span class="text-muted-foreground"> — Know who changed what, when</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500">
                                    <Check class="h-3.5 w-3.5 text-white" />
                                </div>
                                <div>
                                    <span class="font-medium">Real-time collaboration</span>
                                    <span class="text-muted-foreground"> — Comments, mentions, and live updates</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Dashboard Mockup -->
                    <div
                        data-animate="about-mockup"
                        class="transition-all duration-700"
                        :class="isVisible('about-mockup') ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'"
                    >
                        <div class="overflow-hidden rounded-xl border border-border/50 bg-card shadow-2xl shadow-indigo-500/5">
                            <div class="flex items-center gap-2 border-b border-border/50 bg-muted/50 px-4 py-3">
                                <div class="h-3 w-3 rounded-full bg-red-400/80" />
                                <div class="h-3 w-3 rounded-full bg-yellow-400/80" />
                                <div class="h-3 w-3 rounded-full bg-green-400/80" />
                                <span class="ml-2 text-xs text-muted-foreground">Dashboard Overview</span>
                            </div>
                            <div class="p-6">
                                <div class="mb-5 flex items-center justify-between">
                                    <div>
                                        <div class="h-4 w-32 rounded bg-foreground/10" />
                                        <div class="mt-2 h-3 w-48 rounded bg-muted-foreground/10" />
                                    </div>
                                    <div class="h-8 w-24 rounded-lg bg-primary/15" />
                                </div>
                                <!-- Stats cards -->
                                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                                    <div class="rounded-xl border border-border/50 bg-background p-3">
                                        <div class="text-[10px] font-medium text-muted-foreground">To Do</div>
                                        <div class="mt-1 text-xl font-bold">12</div>
                                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                            <div class="h-full w-1/4 rounded-full bg-blue-500" />
                                        </div>
                                    </div>
                                    <div class="rounded-xl border border-border/50 bg-background p-3">
                                        <div class="text-[10px] font-medium text-muted-foreground">In Progress</div>
                                        <div class="mt-1 text-xl font-bold text-amber-500">5</div>
                                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                            <div class="h-full w-2/5 rounded-full bg-amber-500" />
                                        </div>
                                    </div>
                                    <div class="rounded-xl border border-border/50 bg-background p-3">
                                        <div class="text-[10px] font-medium text-muted-foreground">Completed</div>
                                        <div class="mt-1 text-xl font-bold text-emerald-500">28</div>
                                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                            <div class="h-full w-4/5 rounded-full bg-emerald-500" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Task list -->
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="h-4 w-4 rounded border-2 border-amber-400" />
                                        <div class="h-3 flex-1 rounded bg-foreground/8" />
                                        <div class="rounded bg-amber-100 px-1.5 py-0.5 text-[9px] text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">Medium</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500" />
                                    </div>
                                    <div class="flex items-center gap-3 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="h-4 w-4 rounded border-2 border-red-400" />
                                        <div class="h-3 w-3/4 rounded bg-foreground/8" />
                                        <div class="rounded bg-red-100 px-1.5 py-0.5 text-[9px] text-red-600 dark:bg-red-950/50 dark:text-red-400">High</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500" />
                                    </div>
                                    <div class="flex items-center gap-3 rounded-lg border border-border/50 bg-background p-2.5">
                                        <div class="h-4 w-4 rounded border-2 border-emerald-400 bg-emerald-400">
                                            <Check class="h-full w-full p-0.5 text-white" />
                                        </div>
                                        <div class="h-3 w-2/3 rounded bg-foreground/5 line-through" />
                                        <div class="rounded bg-emerald-100 px-1.5 py-0.5 text-[9px] text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">Done</div>
                                        <div class="h-5 w-5 rounded-full bg-gradient-to-br from-rose-500 to-pink-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="testimonials-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('testimonials-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                        <MessageSquare class="h-3.5 w-3.5" />
                        Testimonials
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                        Loved by teams everywhere
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        See what engineering teams are saying about DevCollab.
                    </p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="(testimonial, index) in testimonials"
                        :key="testimonial.name"
                        :data-animate="`testimonial-${index}`"
                        class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card p-6 transition-all duration-500 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :class="isVisible(`testimonial-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 150}ms` }"
                    >
                        <!-- Quote decoration -->
                        <Quote class="absolute -right-2 -top-2 h-20 w-20 rotate-180 text-primary/5" />

                        <!-- Stars -->
                        <div class="relative mb-4 flex gap-1">
                            <Star
                                v-for="n in testimonial.rating"
                                :key="n"
                                class="h-4 w-4 fill-amber-400 text-amber-400"
                            />
                        </div>

                        <!-- Quote text -->
                        <p class="relative mb-6 text-sm leading-relaxed text-muted-foreground">
                            "{{ testimonial.quote }}"
                        </p>

                        <!-- Author -->
                        <div class="relative flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br text-sm font-semibold text-white"
                                :class="testimonial.avatarColor"
                            >
                                {{ testimonial.avatar }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold">{{ testimonial.name }}</div>
                                <div class="text-xs text-muted-foreground">{{ testimonial.role }} at {{ testimonial.company }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="relative overflow-hidden bg-muted/30 py-24 sm:py-32">
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-b from-indigo-500/5 to-transparent blur-3xl" />
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="pricing-header"
                    class="mx-auto mb-16 max-w-2xl text-center transition-all duration-700"
                    :class="isVisible('pricing-header') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                        <BarChart3 class="h-3.5 w-3.5" />
                        Pricing
                    </div>
                    <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                        Simple, transparent pricing
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Choose the plan that fits your team. No hidden fees, cancel anytime.
                    </p>
                </div>

                <div class="mx-auto grid max-w-5xl gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="(plan, index) in pricingPlans"
                        :key="plan.name"
                        :data-animate="`pricing-${index}`"
                        class="relative transition-all duration-500"
                        :class="isVisible(`pricing-${index}`) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: `${index * 150}ms` }"
                    >
                        <!-- Most Popular badge -->
                        <div
                            v-if="plan.highlighted"
                            class="absolute -top-4 left-1/2 z-10 -translate-x-1/2 whitespace-nowrap rounded-full bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-1.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/25"
                        >
                            Most Popular
                        </div>

                        <div
                            class="group flex h-full flex-col overflow-hidden rounded-2xl border bg-card text-card-foreground transition-all duration-300"
                            :class="plan.highlighted
                                ? 'border-primary/50 shadow-xl shadow-primary/10'
                                : 'border-border/50 shadow-sm hover:-translate-y-1 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/10'"
                        >
                            <!-- Gradient top accent for highlighted -->
                            <div
                                v-if="plan.highlighted"
                                class="h-1 w-full shrink-0 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500"
                            />

                            <!-- Card inner content with uniform padding -->
                            <div class="flex flex-1 flex-col p-6 sm:p-8">
                                <!-- Header -->
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold">{{ plan.name }}</h3>
                                    <div class="mt-4">
                                        <span class="text-5xl font-extrabold tracking-tight">{{ plan.price }}</span>
                                        <span class="text-muted-foreground">{{ plan.period }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-muted-foreground">
                                        {{ plan.description }}
                                    </p>
                                </div>

                                <!-- Features list -->
                                <ul class="mt-8 flex-1 space-y-4">
                                    <li
                                        v-for="feat in plan.features"
                                        :key="feat"
                                        class="flex items-center gap-3 text-sm"
                                    >
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full" :class="plan.highlighted ? 'bg-primary/15 text-primary' : 'bg-muted text-muted-foreground'">
                                            <Check class="h-3 w-3" />
                                        </div>
                                        {{ feat }}
                                    </li>
                                </ul>

                                <!-- CTA button -->
                                <div class="mt-8">
                                    <Button
                                        :variant="plan.ctaVariant"
                                        as-child
                                        class="w-full"
                                        :class="plan.highlighted ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30 border-0' : ''"
                                    >
                                        <Link :href="register()">{{ plan.cta }}</Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="relative overflow-hidden py-24 sm:py-32">
            <div class="absolute inset-0 -z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-background via-primary/5 to-background" />
                <div class="absolute left-1/2 top-1/2 h-[400px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-indigo-500/10 via-violet-500/5 to-transparent blur-3xl" />
            </div>

            <div
                data-animate="final-cta"
                class="mx-auto max-w-3xl px-4 text-center transition-all duration-700 sm:px-6"
                :class="isVisible('final-cta') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em">
                    Ready to ship faster?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    Join thousands of teams using DevCollab to build better software, faster. Free to start, no credit card required.
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Button size="lg" as-child class="h-12 w-full bg-gradient-to-r from-indigo-600 to-violet-600 px-8 text-base text-white shadow-lg shadow-indigo-500/25 transition-all hover:shadow-xl hover:shadow-indigo-500/30 sm:w-auto">
                        <Link :href="register()">
                            Get Started Free
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border/50 bg-card/50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Brand -->
                    <div class="sm:col-span-2">
                        <div class="flex items-center gap-2">
                            <AppLogoIcon class="h-7 w-7" />
                            <span class="text-lg font-bold tracking-tight">DevCollab</span>
                        </div>
                        <p class="mt-3 max-w-sm text-sm leading-relaxed text-muted-foreground">
                            The modern project management platform built for development teams that ship great software.
                        </p>
                        <div class="mt-4 flex items-center gap-3 text-sm text-muted-foreground">
                            <Globe class="h-4 w-4" />
                            Built with Laravel, Vue, and Tailwind CSS
                        </div>
                    </div>

                    <!-- Product Links -->
                    <div>
                        <h4 class="mb-4 text-sm font-semibold">Product</h4>
                        <div class="flex flex-col gap-3">
                            <button class="text-left text-sm text-muted-foreground transition-colors hover:text-foreground" @click="scrollTo('#features')">Features</button>
                            <button class="text-left text-sm text-muted-foreground transition-colors hover:text-foreground" @click="scrollTo('#testimonials')">Testimonials</button>
                            <button class="text-left text-sm text-muted-foreground transition-colors hover:text-foreground" @click="scrollTo('#pricing')">Pricing</button>
                        </div>
                    </div>

                    <!-- Account Links -->
                    <div>
                        <h4 class="mb-4 text-sm font-semibold">Account</h4>
                        <div class="flex flex-col gap-3">
                            <Link :href="login()" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Log in</Link>
                            <Link v-if="canRegister" :href="register()" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Create Account</Link>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-border/50 pt-8 text-sm text-muted-foreground md:flex-row">
                    <p>&copy; {{ new Date().getFullYear() }} DevCollab. All rights reserved.</p>
                    <div class="flex items-center gap-6">
                        <span>Privacy Policy</span>
                        <span>Terms of Service</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
