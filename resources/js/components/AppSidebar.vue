<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Candidates',
        href: '/dashboard/candidates',
        icon: LayoutGrid,
    },
    {
        title: 'Managers',
        href: '/dashboard/managers',
        icon: LayoutGrid,
    },
    {
        title: 'Positions',
        href: '/dashboard/positions',
        icon: LayoutGrid,
    },
    {
        title: 'Role',
        href: '/dashboard/roles',
        icon: LayoutGrid,
    },
    {
        title: 'Department',
        href: '/dashboard/departments',
        icon: LayoutGrid,
    },
    {
        title: 'Client',
        href: '/dashboard/clients',
        icon: LayoutGrid,
    },
    {
        title: 'Question',
        href: '/dashboard/questions',
        icon: LayoutGrid,
    }
];

const managerNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/manager/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Candidates',
        href: '/manager/dashboard/candidates',
        icon: LayoutGrid,
    },
]

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

const page = usePage();

const user = computed(() => page.props.auth.user);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link v-if="user.role.name == 'admin'" :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                        <Link v-if="user.role.name == 'manager'" :href="route('manager.dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-if="user.role.name == 'admin'" :items="adminNavItems" />
            <NavMain v-if="user.role.name == 'manager'" :items="managerNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
