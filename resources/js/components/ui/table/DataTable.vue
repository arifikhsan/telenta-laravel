<script setup lang="ts" generic="TData, TValue">
import { valueUpdater } from '@/lib/utils';
import type { ColumnDef, ColumnFiltersState, SortingState, VisibilityState } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import DebouncedInput from '@/components/ui/table/DebouncedInput.vue';

const props = defineProps<{
  title?: string | undefined;
  description?: string | undefined;
  columns: ColumnDef<TData, TValue>[];
  data: TData[];
}>();

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const globalFilter = ref('');

const table = useVueTable({
  data: props.data,
  columns: props.columns,
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
  onGlobalFilterChange: (updaterOrValue) => valueUpdater(updaterOrValue, globalFilter),
  state: {
    get sorting() {
      return sorting.value;
    },
    get columnFilters() {
      return columnFilters.value;
    },
    get columnVisibility() {
      return columnVisibility.value;
    },
    get rowSelection() {
      return rowSelection.value;
    },
    get globalFilter() {
      return globalFilter.value;
    },
  },
});
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ props.title ? props.title : 'Table Title' }}</CardTitle>
      <CardDescription>{{ props.description ? props.description : 'Table Description.' }}</CardDescription>
    </CardHeader>
    <CardContent>
      <div class="w-full">
        <div class="mb-4 flex items-center gap-4">
          <DebouncedInput
            :model-value="globalFilter ?? ''"
            @update:model-value="(value: string) => (globalFilter = String(value))"
            class="max-w-xs"
            placeholder="Search all columns..."
          />
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="outline" class="ml-auto"> Columns <ChevronDown class="ml-2 h-4 w-4" /> </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuCheckboxItem
                v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                :key="column.id"
                class="capitalize"
                :model-value="column.getIsVisible()"
                @update:model-value="
                  (value) => {
                    column.toggleVisibility(!!value);
                  }
                "
              >
                {{ column.id }}
              </DropdownMenuCheckboxItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
        <div class="rounded-md border">
          <Table>
            <TableHeader>
              <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                <TableHead v-for="header in headerGroup.headers" :key="header.id" class="[&:has([role=checkbox])]:pl-3">
                  <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <template v-if="table.getRowModel().rows?.length">
                <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() && 'selected'">
                  <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id" class="[&:has([role=checkbox])]:pl-3">
                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                  </TableCell>
                </TableRow>
              </template>

              <TableRow v-else>
                <TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
          <div class="flex-1 text-sm text-muted-foreground">
            {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }} row(s) selected.
          </div>
          <div class="space-x-2">
            <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()"> Previous </Button>
            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()"> Next </Button>
          </div>
        </div>
      </div>
    </CardContent>
  </Card>
</template>
