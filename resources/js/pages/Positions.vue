<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import dayjs from 'dayjs';
import { Position } from '@/types/entity/position';

const formatDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};

defineProps({
    positions: {
        type: Array as () => Position[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Positions" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your positions.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Id </TableHead>
                        <TableHead> Name </TableHead>
                        <TableHead> Created At </TableHead>
                        <TableHead> Updated At </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the positions and display them -->
                    <TableRow v-for="position in positions" :key="position.id">
                        <TableCell>
                            {{ position.id }}
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ position.name }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(position.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(position.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
