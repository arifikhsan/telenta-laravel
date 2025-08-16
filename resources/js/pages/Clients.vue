<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { ClientEntity } from '@/types/entity/client-entity';
import { Head } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTable from '@/components/ui/table/DataTable.vue';

defineProps({
  clients: {
    type: Array as () => ClientEntity[],
    required: true,
  },
});

const columnHelper = createColumnHelper<ClientEntity>();

const columns: ColumnDef<ClientEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    cell: ({ row }) => h('div', { class: 'capitalize font-medium' }, row.getValue('name')),
  }),
  columnHelper.accessor('created_at', {
    header: 'Created At',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('created_at'))),
  }),
  columnHelper.accessor('updated_at', {
    header: 'Updated At',
    cell: ({ row }) => h('div', formatStandardDate(row.getValue('updated_at'))),
  }),
];
</script>

<template>
  <Head title="Clients" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <DataTable title="Clients" description="User clients" :columns="columns" :data="clients" />
    </div>
  </AppLayout>
</template>
