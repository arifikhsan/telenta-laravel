<script setup lang="ts">
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { QuestionEntity } from '@/types/entity/question-entity';
import { Head } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

defineProps({
  questions: {
    type: Array as () => QuestionEntity[], // Specify the type of roles as an array of Role objects
    required: true,
  },
});

const columnHelper = createColumnHelper<QuestionEntity>();

const columns: ColumnDef<QuestionEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor('question', {
    header: 'Question',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('question')),
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
  <Head title="Question" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <DataTable title="Question" description="Interview Question" :columns="columns" :data="questions" />
    </div>
  </AppLayout>
</template>
