<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import dayjs from 'dayjs';
import { Candidate } from '@/types/entity/candidate';

const formatDate = (date: string): string => {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss'); // Format as needed
};

defineProps({
    candidates: {
        type: Array as () => Candidate[], // Specify the type of roles as an array of Role objects
        required: true,
    },
});
</script>

<template>
    <Head title="Manager Candidates" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Table>
                <TableCaption>A list of your candidates.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Id </TableHead>
                        <TableHead> Name </TableHead>
                        <TableHead> Position </TableHead>
                        <TableHead> Status </TableHead>
                        <TableHead> CV </TableHead>
                        <TableHead> Manager </TableHead>
                        <TableHead> Days Required </TableHead>
                        <TableHead> Proposed Date </TableHead>
                        <TableHead> CV Scan Date </TableHead>
                        <TableHead> HR Interview Date </TableHead>
                        <TableHead> Created At </TableHead>
                        <TableHead> Updated At </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <!-- Loop through the candidates and display them -->
                    <TableRow v-for="candidate in candidates" :key="candidate.id">
                        <TableCell>
                            {{ candidate.id }}
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ candidate.name }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.position.name }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.status }}
                        </TableCell>
                        <TableCell>
                            <!-- Display CV link if exists -->
                            <a v-if="candidate.cv_url"
                               :href="candidate.cv_url"
                               target="_blank"
                               class="text-blue-600 hover:underline">
                                View CV
                            </a>
                            <!-- Otherwise, display 'No CV' -->
                            <span v-else>No CV</span>
                        </TableCell>
                        <TableCell>
                            {{ candidate.manager.name }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.days_required }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.proposed_date }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.cv_review_date }}
                        </TableCell>
                        <TableCell>
                            {{ candidate.hr_interview_date }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(candidate.created_at) }}
                        </TableCell>
                        <TableCell>
                            {{ formatDate(candidate.updated_at) }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
