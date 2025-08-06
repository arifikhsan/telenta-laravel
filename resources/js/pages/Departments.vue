<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import dayjs from 'dayjs';
import { Department } from '@/types/entity/department';

const formatDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};

defineProps({
    departments: {
        type: Array as () => Department[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Departments" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your departments.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Id </TableHead>
                        <TableHead> Name </TableHead>
                        <TableHead> Created At </TableHead>
                        <TableHead> Updated At </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the departments and display them -->
                    <TableRow v-for="department in departments" :key="department.id">
                        <TableCell>
                            {{ department.id }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ department.name }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell>
                            {{ formatDate(department.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(department.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
