<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { CandidateEntity } from '@/types/entity/candidate-entity';
import { ManagerEntity } from '@/types/entity/manager-entity';
import { PositionEntity } from '@/types/entity/position-entity';
import { Head } from '@inertiajs/vue3';
import { getLocalTimeZone } from '@internationalized/date';
import axios from 'axios';
import { toast } from 'vue-sonner';
import CandidateForm from './CandidateForm.vue';

const props = defineProps({
  candidate: {
    type: Object as () => CandidateEntity,
    required: true,
  },
  positions: {
    type: Array as () => PositionEntity[],
    required: true,
  },
  managers: {
    type: Array as () => ManagerEntity[],
    required: true,
  },
});

const onSubmit = async (values: any) => {
  const formData = new FormData();
  for (const key in values) {
    if (values[key] instanceof File) {
      formData.append(key, values[key]);
    } else if (values[key]?.toDate) {
      formData.append(key, values[key].toDate(getLocalTimeZone()).toISOString());
    } else {
      formData.append(key, values[key]);
    }
  }

  try {
    await axios.post(`/dashboard/candidates/${props.candidate.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    toast.success('Candidate updated successfully!');
    setTimeout(() => {
      window.location.href = '/dashboard/candidates';
    }, 1000);
  } catch (err: any) {
    toast.error('Error updating candidate', { description: err.toString() });
  }
};
</script>

<template>
  <Head title="Edit Candidate" />
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="max-w-sm">
        <CandidateForm :model-value="candidate" :managers="managers" :positions="positions" @submit="onSubmit" />
      </div>
    </div>
  </AppLayout>
</template>
