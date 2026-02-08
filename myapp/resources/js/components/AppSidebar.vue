<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Clock,
    FolderKanban,
    LayoutGrid,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import NotificationPanel from '@/components/NotificationPanel.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useRecentProjects } from '@/composables/useRecentItems';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const unreadNotificationCount = computed(
    () => (page.props.unreadNotificationCount as number) ?? 0,
);

const { recentProjects } = useRecentProjects();

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Projects',
            href: '/projects',
            icon: FolderKanban,
        },
    ];

    if (user.value?.role === 'admin') {
        items.push({
            title: 'User Management',
            href: '/admin/users',
            icon: Users,
        });
    }

    return items;
});

const recentNavItems = computed<NavItem[]>(() =>
    recentProjects.value.map((p) => ({
        title: p.name,
        href: `/projects/${p.id}`,
        icon: FolderKanban,
    })),
);

const footerNavItems: NavItem[] = [
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />

            <div v-if="recentNavItems.length > 0" class="px-3 pt-4">
                <p class="mb-1 px-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                    Recent
                </p>
                <NavMain :items="recentNavItems" />
            </div>
        </SidebarContent>

        <SidebarFooter>
            <div class="flex items-center justify-center px-2 py-1">
                <NotificationPanel :unread-count="unreadNotificationCount" />
            </div>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
