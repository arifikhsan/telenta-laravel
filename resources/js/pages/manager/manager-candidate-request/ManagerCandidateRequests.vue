<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { type BreadcrumbItem } from '@/types';
import { ManagerCandidateRequestEntity } from '@/types/entity/manager-candidate-request-entity';
import { Head, Link, router } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const columnHelper = createColumnHelper<ManagerCandidateRequestEntity>();

const columns: ColumnDef<ManagerCandidateRequestEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('status', {
    header: 'Status',
    cell: ({ row }) => {
      const status = row.getValue('status');

      return h(Badge, { class: 'capitalize' }, { default: () => status });
    },
  }),
  columnHelper.accessor('requested_count', {
    header: 'Requested Count',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('requested_count')),
  }),
  columnHelper.accessor('fulfilled_count', {
    header: 'Fulfilled Count',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('fulfilled_count')),
  }),
  columnHelper.accessor('date_requested', {
    header: 'Date Requested',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('date_requested'))),
  }),
  columnHelper.display({
    id: 'actions',
    header: 'Actions',
    enablePinning: true, // allow pinning
    cell: ({ row }) => {
      return h('div', { class: 'flex gap-2' }, [
        // how to make this link only show if status == pending
        row.original.status === 'pending' && h(
          'button',
          {
            onclick: () => markAsCancelled(row.original.id),
            class: 'text-red-600 hover:underline',
          },
          'Batalkan',
        ),
        row.original.status === 'accepted' && h(
          Link,
          {
            href: route('manager.dashboard.manager-candidate-requests', row.original.id),
            class: 'text-green-600 hover:underline',
          },
          'Lihat Kandidat',
        ),
      ]);
    },
  }),
];

const markAsCancelled = (id: number) => {
  router.post(route('manager.dashboard.manager-candidate-requests.mark-as-cancelled', id), {}, {
    onSuccess: () => {
      // Handle success
      toast.success('Manager Candidate Request marked as cancelled');

      setTimeout(() => {
        location.reload();
      }, 2000);
    },
    onError: () => {
      // Handle error
      toast.error('Failed to mark Manager Candidate Request as cancelled');
    },
  });
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'ManagerCandidateRequest',
    href: '/manager-candidate-requests',
  },
];

const props = defineProps<{
  managerCandidateRequests: ManagerCandidateRequestEntity[];
}>();
</script>

<template>
  <Head title="Manager Candidate Requests" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex justify-end">
        <Link :href="route('manager.dashboard.manager-candidate-requests.create')">
          <Button variant="default">Tambah Permintaan Kandidat Baru</Button>
        </Link>
      </div>
      <DataTable
        title="Manager Candidate Request"
        description="Manager Candidate Request"
        :columns="columns"
        :data="props.managerCandidateRequests"
      />
    </div>
  </AppLayout>
</template>
