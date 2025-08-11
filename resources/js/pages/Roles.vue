<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Role } from '@/types/entity/role-entity';
import dayjs from 'dayjs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Role',
        href: '/roles',
    },
];

const formatDate =(date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
}

defineProps({
    roles: {
        type: Array as () => Role[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <Table>
                <TableCaption>A list of your roles.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">
                            Id
                        </TableHead>
                        <TableHead>
                            Name
                        </TableHead>
                        <TableHead>
                            Created At
                        </TableHead>
                        <TableHead>
                            Updated At
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the roles and display them -->
                    <TableRow v-for="role in roles" :key="role.id">
                        <TableCell>
                            {{ role.id }} <!-- Display the role name -->
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ role.name }} <!-- Display the role name -->
                        </TableCell>
                        <TableCell>
                            {{ formatDate(role.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(role.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
