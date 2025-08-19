<script setup lang="ts">
import DataTable from '@/components/ui/table/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDate } from '@/lib/date-util';
import { QuestionPositionMapEntity } from '@/types/entity/questionPositionMap-entity';
import { Head } from '@inertiajs/vue3';
import { ColumnDef, createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

defineProps({
  questionPositionMaps: {
    type: Array as () => QuestionPositionMapEntity[], // Specify the type of roles as an array of Role objects
    required: true,
  },
});

const columnHelper = createColumnHelper<QuestionPositionMapEntity>();

const columns: ColumnDef<QuestionPositionMapEntity, any>[] = [
  columnHelper.accessor('id', {
    header: 'Id',
    cell: ({ row }) => h('div', row.getValue('id')),
  }),
  columnHelper.accessor((row) => row.position.name, {
    id: 'position.name',
    header: 'Position',
    cell: ({ row }) => {
      return h('div', { class: 'capitalize' }, row.getValue('position.name'));
    },
  }),
  columnHelper.accessor((row) => row.question.question, {
    id: 'question.question',
    header: 'Question',
    cell: ({ row }) => {
      return h('div', { class: 'capitalize' }, row.getValue('question.question'));
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
  <Head title="Question to Position" />

  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <DataTable title="Question to Position" description="List Question to related Position" :columns="columns" :data="questionPositionMaps" />
    </div>
  </AppLayout>
</template>
