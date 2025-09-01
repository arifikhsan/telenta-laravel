<script setup lang="ts">
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { type BreadcrumbItem } from '@/types';
import { DepartmentEntity } from '@/types/entity/department-entity';
import { Head } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

const columnHelper = createColumnHelper<DepartmentEntity>();

const columns: ColumnDef<DepartmentEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('name')),
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

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Department',
    href: '/departments',
  },
];


const props = defineProps<{
  departments: DepartmentEntity[];
}>();

</script>

<template>
  <Head title="Departments" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <DataTable title="Department" description="All departments" :columns="columns" :data="props.departments" />
    </div>
  </AppLayout>
</template>
