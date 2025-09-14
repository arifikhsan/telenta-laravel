<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { FormDescription, FormField } from '@/components/ui/form';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatStandardDateFromDate } from '@/lib/date-util';
import { router } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { toast } from 'vue-sonner';
import z from 'zod';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { PositionEntity } from '@/types/entity/position-entity';

const formSchema = toTypedSchema(
  z.object({
    position_id: z.number().min(1, 'Position is required'),
    requested_count: z.number().min(1, 'Requested count must be at least 1'),
    date_requested: z.string().nonempty('Date requested is required'),
    note: z.string().optional(),
    level: z.string(),
    hiring_type: z.string(),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    position_id: 1,
    requested_count: 1,
    date_requested: formatStandardDateFromDate(new Date()),
    note: '',
    level: 'junior',
    hiring_type: 'new',
  },
});

const onSubmit = form.handleSubmit((values) => {
  router.post(route('manager.dashboard.manager-candidate-requests.store'), values, {
    onSuccess: () => {
      // Handle success
      toast.success('Manager Candidate Request created successfully');
    },
    onError: () => {
      // Handle error
      toast.error('Failed to create Manager Candidate Request');
    },
  });
});

const props = defineProps<{
  positions: PositionEntity[];
}>();
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="max-w-sm">
        <h1 class="scroll-m-20 text-2xl font-semibold tracking-tight">Create Manager Candidate Request</h1>
        <form @submit="onSubmit" class="mt-8 space-y-6">
          <FormField v-slot="{ componentField }" name="position_id">
            <FormItem>
              <FormLabel>Position</FormLabel>

              <Select v-bind="componentField">
                <FormControl class="w-full">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih posisi kandidat" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  <SelectGroup>
                    <SelectItem v-for="(position, index) in props.positions" :key="index" :value="position.id">
                      {{ position.name }}
                    </SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <FormDescription> Pilih posisi kandidat yang diinginkan. </FormDescription>
              <FormMessage />
            </FormItem>
          </FormField>
          <FormField v-slot="{ componentField }" name="requested_count">
            <FormItem>
              <FormLabel>Candidate Count</FormLabel>
              <FormControl>
                <Input type="number" placeholder="1" v-bind="componentField" />
              </FormControl>
              <FormDescription> Tentukan jumlah kandidat yang diinginkan. </FormDescription>
              <FormMessage />
            </FormItem>
          </FormField>
          <FormField v-slot="{ componentField }" name="level">
            <FormItem>
              <FormLabel>Level</FormLabel>

              <Select v-bind="componentField">
                <FormControl class="w-full">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih level kandidat" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  <SelectGroup>
                    <SelectItem value="junior">Junior</SelectItem>
                    <SelectItem value="middle">Middle</SelectItem>
                    <SelectItem value="senior">Senior</SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <FormDescription> Pilih level kandidat yang diinginkan. </FormDescription>
              <FormMessage />
            </FormItem>
          </FormField>
          <FormField v-slot="{ componentField }" name="hiring_type">
            <FormItem>
              <FormLabel>Hiring Type</FormLabel>

              <Select v-bind="componentField">
                <FormControl class="w-full">
                  <SelectTrigger>
                    <SelectValue placeholder="Jenis rekrutmen" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  <SelectGroup>
                    <SelectItem value="new">New</SelectItem>
                    <SelectItem value="replacement">Replacement</SelectItem>
                  </SelectGroup>
                </SelectContent>
              </Select>
              <FormDescription> Pilih jenis rekrutmen. </FormDescription>
              <FormMessage />
            </FormItem>
          </FormField>
          <FormField v-slot="{ componentField }" name="note">
            <FormItem>
              <FormLabel>Catatan</FormLabel>
              <FormControl>
                <Textarea v-bind="componentField" />
              </FormControl>
              <FormDescription> Masukkan nama kandidat jika jenis rekrutmen adalah replacement. </FormDescription>
              <FormMessage />
            </FormItem>
          </FormField>

          <Button type="submit">Submit</Button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
