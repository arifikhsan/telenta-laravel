<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import dayjs from 'dayjs';
import { Manager } from '@/types';

const formatDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};

defineProps({
    managers: {
        type: Array as () => Manager[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Managers" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your managers.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Id </TableHead>
                        <TableHead> Name </TableHead>
                        <TableHead> Department </TableHead>
                        <TableHead> Created At </TableHead>
                        <TableHead> Updated At </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the managers and display them -->
                    <TableRow v-for="manager in managers" :key="manager.id">
                        <TableCell>
                            {{ manager.id }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ manager.name }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell> {{ manager.department ? manager.department.name : 'No Department' }} </TableCell>
                        <TableCell>
                            {{ formatDate(manager.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(manager.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
