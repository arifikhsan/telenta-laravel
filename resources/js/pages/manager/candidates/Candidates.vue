<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { getStatusLabel } from '@/lib/candidate-util';
import { formatStandardDate } from '@/lib/date-util';
import { CandidateEntity } from '@/types/entity/candidate-entity';
import { Head, Link } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

defineProps({
  candidates: {
    type: Array as () => CandidateEntity[],
    required: true,
  },
});

const columnHelper = createColumnHelper<CandidateEntity>();

const columns: ColumnDef<CandidateEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('name')),
  }),
  columnHelper.accessor('status', {
    header: 'Status',
    cell: ({ row }) => {
      const status = row.getValue('status') as string;

      return h(
        Badge,
        { class: 'capitalize' },
        { default: () => getStatusLabel(status) },
      );
    },
  }),
  columnHelper.accessor('cv_url', {
    header: 'CV',
    cell: ({ row }) => {
      const cvUrl = row.getValue('cv_url') as string | null;

      if (cvUrl) {
        return h(
          'a',
          {
            href: cvUrl,
            target: '_blank',
            class: 'text-blue-600 hover:underline',
          },
          'View CV',
        );
      }

      return h('span', 'No CV');
    },
  }),
  columnHelper.accessor((row) => row.position.name, {
    id: 'position.name',
    header: 'Position',
    cell: ({ row }) => {
      return h('div', { class: 'capitalize' }, row.getValue('position.name'));
    },
  }),
  columnHelper.accessor((row) => row.manager.name, {
    id: 'manager.name',
    header: 'Manager',
    cell: ({ row }) => {
      return h('div', { class: 'capitalize' }, row.getValue('manager.name'));
    },
  }),
  columnHelper.accessor('days_required', {
    header: 'Days Required',
    cell: ({ row }) => h('div', row.getValue('days_required')),
  }),
  columnHelper.accessor('proposed_date', {
    header: 'Proposed Date',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('proposed_date'))),
  }),
  columnHelper.accessor('cv_review_date', {
    header: 'CV Review Date',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('cv_review_date'))),
  }),
  columnHelper.accessor('hr_interview_date', {
    header: 'HR Interview Date',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('hr_interview_date'))),
  }),
  columnHelper.accessor('created_at', {
    header: 'Created At',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('created_at'))),
  }),
  columnHelper.accessor('updated_at', {
    header: 'Updated At',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('updated_at'))),
  }),
  columnHelper.display({
    id: 'actions',
    header: 'Actions',
    enablePinning: true, // allow pinning
    cell: ({ row }) => {
      return h('div', { class: 'flex gap-2' }, [
        h(
          Link,
          {
            href: route('dashboard.candidates.edit', row.original.id),
            class: 'text-blue-600 hover:underline',
          },
          'Edit',
        ),
      ]);
    },
  }),
];
</script>

<template>
  <Head title="Candidates" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex justify-end">
        <Link :href="route('dashboard.candidates.create')">
          <Button variant="default">Tambah Kandidat Baru</Button>
        </Link>
      </div>
      <DataTable title="Candidates" description="List of candidates" :columns="columns" :data="candidates" />
    </div>
  </AppLayout>
</template>
