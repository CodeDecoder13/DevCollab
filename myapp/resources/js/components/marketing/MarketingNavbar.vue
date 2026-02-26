<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import {
    Activity,
    ArrowRight,
    BarChart3,
    Building2,
    CheckSquare,
    ChevronDown,
    FolderKanban,
    GitBranch,
    Layers,
    Menu,
    MessageSquare,
    Rocket,
    Users,
    Zap,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import { Button } from '@/components/ui/button';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuList,
    NavigationMenuTrigger,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { cn } from '@/lib/utils';
import { dashboard, login, register } from '@/routes';

const props = withDefaults(
    defineProps<{
        transparent?: boolean;
        canRegister?: boolean;
    }>(),
    {
        transparent: false,
        canRegister: true,
    },
);

const page = usePage();
const { y } = useWindowScroll();
const isScrolled = computed(() => y.value > 50);
const mobileMenuOpen = ref(false);

const isTransparentMode = computed(() => props.transparent && !isScrolled.value);

const triggerOverride = computed(() =>
    isTransparentMode.value
        ? 'bg-transparent text-white/70 hover:bg-white/10 hover:text-white data-[state=open]:bg-white/10 data-[state=open]:text-white focus:bg-white/10 focus:text-white'
        : '',
);

const featuresItems = [
    { icon: FolderKanban, title: 'Kanban Boards', description: 'Visual workflow management with drag-and-drop', href: '/features#kanban' },
    { icon: Layers, title: 'Sprint Planning', description: 'Plan and manage agile sprints effectively', href: '/features#sprints' },
    { icon: CheckSquare, title: 'Issue Tracking', description: 'Track bugs and issues with full context', href: '/features#issues' },
    { icon: GitBranch, title: 'Git Integration', description: 'Connect your repositories seamlessly', href: '/features#git' },
    { icon: MessageSquare, title: 'Real-time Collaboration', description: 'Comments, mentions, and live updates', href: '/features#collaboration' },
    { icon: BarChart3, title: 'Analytics', description: 'Insights and reporting for your projects', href: '/features#analytics' },
];

const solutionsItems = [
    { icon: Zap, title: 'Engineering Teams', description: 'Ship code faster with streamlined workflows', href: '/solutions#engineering' },
    { icon: Rocket, title: 'Product Teams', description: 'Plan, prioritize, and track product roadmaps', href: '/solutions#product' },
    { icon: Activity, title: 'Startups', description: 'Move fast without losing track of work', href: '/solutions#startups' },
    { icon: Building2, title: 'Enterprise', description: 'Scale project management across organizations', href: '/solutions#enterprise' },
];

const simpleLinks = [
    { label: 'Guide', href: '/guide' },
    { label: 'About', href: '/about' },
    { label: 'Contact', href: '/contact' },
];
</script>

<template>
    <nav
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
        :class="
            isScrolled || !transparent
                ? 'border-b border-border/50 bg-background/80 shadow-sm backdrop-blur-xl'
                : 'bg-transparent'
        "
    >
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <Link href="/" class="flex items-center gap-2">
                <AppLogoIcon class="h-8 w-8" />
                <span
                    class="text-lg font-bold tracking-tight transition-colors"
                    :class="isTransparentMode ? 'text-white' : 'text-foreground'"
                >
                    DevCollab
                </span>
            </Link>

            <!-- Desktop Nav -->
            <div class="hidden items-center md:flex">
                <NavigationMenu>
                    <NavigationMenuList>
                        <!-- Features Dropdown -->
                        <NavigationMenuItem>
                            <NavigationMenuTrigger :class="triggerOverride">
                                Features
                            </NavigationMenuTrigger>
                            <NavigationMenuContent class="w-[550px]">
                                <div class="grid grid-cols-2 gap-1 p-3">
                                    <Link
                                        v-for="item in featuresItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-start gap-3 rounded-lg p-3 transition-colors hover:bg-accent"
                                    >
                                        <component :is="item.icon" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
                                        <div>
                                            <div class="text-sm font-medium leading-none">{{ item.title }}</div>
                                            <p class="mt-1.5 text-xs leading-snug text-muted-foreground">{{ item.description }}</p>
                                        </div>
                                    </Link>
                                </div>
                            </NavigationMenuContent>
                        </NavigationMenuItem>

                        <!-- Solutions Dropdown -->
                        <NavigationMenuItem>
                            <NavigationMenuTrigger :class="triggerOverride">
                                Solutions
                            </NavigationMenuTrigger>
                            <NavigationMenuContent class="w-[420px]">
                                <div class="grid gap-1 p-3">
                                    <Link
                                        v-for="item in solutionsItems"
                                        :key="item.title"
                                        :href="item.href"
                                        class="flex items-start gap-3 rounded-lg p-3 transition-colors hover:bg-accent"
                                    >
                                        <component :is="item.icon" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
                                        <div>
                                            <div class="text-sm font-medium leading-none">{{ item.title }}</div>
                                            <p class="mt-1.5 text-xs leading-snug text-muted-foreground">{{ item.description }}</p>
                                        </div>
                                    </Link>
                                </div>
                            </NavigationMenuContent>
                        </NavigationMenuItem>

                        <!-- Simple Links -->
                        <NavigationMenuItem v-for="link in simpleLinks" :key="link.href">
                            <Link
                                :href="link.href"
                                :class="cn(
                                    navigationMenuTriggerStyle(),
                                    triggerOverride,
                                )"
                            >
                                {{ link.label }}
                            </Link>
                        </NavigationMenuItem>
                    </NavigationMenuList>
                </NavigationMenu>
            </div>

            <!-- Right side buttons -->
            <div class="hidden items-center gap-2 md:flex">
                <ThemeToggle />
                <template v-if="page.props.auth.user">
                    <Button as-child>
                        <Link :href="dashboard()">
                            Dashboard
                            <ArrowRight class="ml-1.5 h-4 w-4" />
                        </Link>
                    </Button>
                </template>
                <template v-else>
                    <Button
                        variant="ghost"
                        as-child
                        :class="isTransparentMode ? 'text-white/80 hover:text-white hover:bg-white/10' : ''"
                    >
                        <Link :href="login()">Log in</Link>
                    </Button>
                    <Button
                        v-if="canRegister"
                        as-child
                        class="border-0 bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/30"
                    >
                        <Link :href="register()">
                            Get Started
                            <ArrowRight class="ml-1.5 h-4 w-4" />
                        </Link>
                    </Button>
                </template>
            </div>

            <!-- Mobile -->
            <div class="flex items-center gap-1 md:hidden">
                <ThemeToggle />
                <Sheet v-model:open="mobileMenuOpen">
                    <SheetTrigger as-child>
                        <Button
                            variant="ghost"
                            size="sm"
                            :class="isTransparentMode ? 'text-white hover:bg-white/10' : ''"
                        >
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="right" class="w-72">
                        <SheetHeader>
                            <SheetTitle>Menu</SheetTitle>
                        </SheetHeader>
                        <div class="mt-6 flex flex-col gap-2">
                            <!-- Features Collapsible -->
                            <Collapsible>
                                <CollapsibleTrigger class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                                    Features
                                    <ChevronDown class="h-4 w-4 transition-transform duration-200 [[data-state=open]>&]:rotate-180" />
                                </CollapsibleTrigger>
                                <CollapsibleContent>
                                    <div class="ml-3 flex flex-col gap-1 border-l border-border pl-3 pt-1">
                                        <Link
                                            v-for="item in featuresItems"
                                            :key="item.title"
                                            :href="item.href"
                                            class="rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                            @click="mobileMenuOpen = false"
                                        >
                                            {{ item.title }}
                                        </Link>
                                    </div>
                                </CollapsibleContent>
                            </Collapsible>

                            <!-- Solutions Collapsible -->
                            <Collapsible>
                                <CollapsibleTrigger class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                                    Solutions
                                    <ChevronDown class="h-4 w-4 transition-transform duration-200 [[data-state=open]>&]:rotate-180" />
                                </CollapsibleTrigger>
                                <CollapsibleContent>
                                    <div class="ml-3 flex flex-col gap-1 border-l border-border pl-3 pt-1">
                                        <Link
                                            v-for="item in solutionsItems"
                                            :key="item.title"
                                            :href="item.href"
                                            class="rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                                            @click="mobileMenuOpen = false"
                                        >
                                            {{ item.title }}
                                        </Link>
                                    </div>
                                </CollapsibleContent>
                            </Collapsible>

                            <!-- Simple Links -->
                            <Link
                                v-for="link in simpleLinks"
                                :key="link.href"
                                :href="link.href"
                                class="rounded-lg px-3 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent"
                                @click="mobileMenuOpen = false"
                            >
                                {{ link.label }}
                            </Link>

                            <hr class="my-2 border-border" />

                            <template v-if="page.props.auth.user">
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
</template>
