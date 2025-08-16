<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import BarChartExample from './charts/BarChartExample.vue';
import PieChartExample from './charts/PieChartExample.vue';
import LineChartExample from './charts/LineChartExample.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="grid auto-rows-min gap-4 md:grid-cols-2">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="flex h-full items-center justify-center p-4">
            <PieChartExample />
          </div>
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <div class="flex h-full items-center justify-center p-4">
            <BarChartExample />
          </div>
        </div>
      </div>
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
        <div class="flex h-full items-center justify-center p-4">
          <LineChartExample />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
