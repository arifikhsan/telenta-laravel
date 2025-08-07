<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import dayjs from 'dayjs';
import { Client } from '@/types/entity/client';

const formatDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};

defineProps({
    clients: {
        type: Array as () => Client[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Clients" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your clients.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Id </TableHead>
                        <TableHead> Name </TableHead>
                        <TableHead> Created At </TableHead>
                        <TableHead> Updated At </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the clients and display them -->
                    <TableRow v-for="client in clients" :key="client.id">
                        <TableCell>
                            {{ client.id }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ client.name }}
                            <!-- Display the role name -->
                        </TableCell>
                        <TableCell>
                            {{ formatDate(client.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(client.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
