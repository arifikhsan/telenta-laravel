<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { FormField } from '@/components/ui/form';
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

const formSchema = toTypedSchema(
  z.object({
    requested_count: z.number().min(1, 'Requested count must be at least 1'),
    date_requested: z.string().nonempty('Date requested is required'),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    requested_count: 1,
    date_requested: formatStandardDateFromDate(new Date()),
  },
});

const onSubmit = form.handleSubmit((values) => {
  console.log(values);

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
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="max-w-sm">
        <h1 class="scroll-m-20 text-2xl font-semibold tracking-tight">Create Manager Candidate Request</h1>
        <form @submit="onSubmit" class="mt-8 space-y-6">
          <FormField v-slot="{ componentField }" name="requested_count">
            <FormItem>
              <FormLabel>Candidate Count</FormLabel>
              <FormControl>
                <Input type="number" placeholder="1" v-bind="componentField" />
              </FormControl>
              <FormMessage />
            </FormItem>
          </FormField>

          <Button type="submit">Submit</Button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
