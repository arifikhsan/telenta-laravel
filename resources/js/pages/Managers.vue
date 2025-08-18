<script setup lang="ts">
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { ManagerEntity } from '@/types/entity/manager-entity';
import { Head } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

defineProps({
  managers: {
    type: Array as () => ManagerEntity[],
    required: true,
  },
});

const columnHelper = createColumnHelper<ManagerEntity>();

const columns: ColumnDef<ManagerEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('name')),
  }),
  columnHelper.accessor((row) => row.client.name, {
    id: 'client.name',
    header: 'Client',
    cell: ({ row }) => {
      return h('div', { class: 'capitalize' }, row.getValue('client.name'));
    },
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
  <Head title="Managers" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <DataTable title="Managers" description="User managers with client" :columns="columns" :data="managers" />
    </div>
  </AppLayout>
</template>
