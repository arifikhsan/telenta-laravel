<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { PositionEntity } from '@/types/entity/position-entity';
import { CalendarDate, DateFormatter, DateValue, getLocalTimeZone, ZonedDateTime } from '@internationalized/date';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { PropType, ref, watch } from 'vue';
import * as z from 'zod';

const props = defineProps({
  modelValue: {
    type: Object as PropType<any>, // Candidate initial values
    required: false,
    default: () => ({}),
  },
  managers: {
    type: Array as PropType<Array<{ id: number; name: string }>>,
    required: true,
  },
  positions: {
    type: Array as PropType<PositionEntity[]>,
    required: true,
  },
});

const emit = defineEmits(['submit']);

const formSchema = toTypedSchema(
  z.object({
    name: z.string().min(2).max(50),
    manager_id: z.number().min(1, 'Manager is required'),
    position_id: z.number().min(1, 'Position is required'),
    status: z.string().min(1, 'Status is required'),
    days_required: z.number().min(1, 'Days Required is required'),
    proposed_date: z
      .custom<DateValue>(
        (value) => {
          if (value instanceof Date) {
            return true; // Can be a Date object
          }
          return value && value.toDate instanceof Function; // Or if it's a DateValue object
        },
        {
          message: 'Invalid proposed date',
        },
      )
      .optional(),
    cv_review_date: z
      .custom<DateValue>(
        (value) => {
          if (value instanceof Date) {
            return true; // Can be a Date object
          }
          return value && value.toDate instanceof Function; // Or if it's a DateValue object
        },
        {
          message: 'Invalid cv review date',
        },
      )
      .optional(),
    hr_interview_date: z
      .custom<DateValue>(
        (value) => {
          if (value instanceof Date) {
            return true; // Can be a Date object
          }
          return value && value.toDate instanceof Function; // Or if it's a DateValue object
        },
        {
          message: 'Invalid hr review date',
        },
      )
      .optional(),
    cv: z
      .instanceof(File) // Ensure it's a file
      .refine((file) => !file || file.size <= 10 * 1024 * 1024, 'CV file should be less than 10MB')
      .nullable(),
  }),
);

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.modelValue?.name || '',
    manager_id: props.modelValue?.manager_id || 0,
    position_id: props.modelValue?.position_id || 0,
    status: props.modelValue?.status || '',
    days_required: props.modelValue?.days_required || 1,
    proposed_date: props.modelValue?.proposed_date,
    cv_review_date: props.modelValue?.cv_review_date,
    hr_interview_date: props.modelValue?.hr_interview_date,
    cv: null,
  },
});

const proposedDateValue = ref<CalendarDate | ZonedDateTime | undefined>(
  props.modelValue?.proposed_date
    ? new CalendarDate(props.modelValue.proposed_date.year, props.modelValue.proposed_date.month, props.modelValue.proposed_date.day)
    : undefined,
);
const cvReviewDateValue = ref<CalendarDate | ZonedDateTime | undefined>(
  props.modelValue?.cv_review_date
    ? new CalendarDate(props.modelValue.cv_review_date.year, props.modelValue.cv_review_date.month, props.modelValue.cv_review_date.day)
    : undefined,
);
const hrInterviewDateValue = ref<CalendarDate | ZonedDateTime | undefined>(
  props.modelValue?.hr_interview_date
    ? new CalendarDate(props.modelValue.hr_interview_date.year, props.modelValue.hr_interview_date.month, props.modelValue.hr_interview_date.day)
    : undefined,
);
const cvFile = ref<File | null>(null);

watch(proposedDateValue, (val) => {
  form.setFieldValue('proposed_date', val);
});
watch(cvReviewDateValue, (val) => {
  form.setFieldValue('cv_review_date', val);
});
watch(hrInterviewDateValue, (val) => {
  form.setFieldValue('hr_interview_date', val);
});
watch(cvFile, (val) => (form.values.cv = val));

const df = new DateFormatter('en-US', { dateStyle: 'long' });

const statusOptions = [
  { value: 'cv_reviewed', label: 'CV Reviewed' },
  { value: 'hr_interviewed', label: 'HR Interviewed' },
  { value: 'hired', label: 'Hired' },
];

const onFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (input.files?.[0]) cvFile.value = input.files[0];
};

const onSubmit = form.handleSubmit((values) => {
  emit('submit', values);
});
</script>

<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    {{ form.values }}

    <!-- Name -->
    <FormField name="name" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Name</FormLabel>
        <FormControl>
          <Input type="text" v-bind="componentField" />
        </FormControl>
        <FormDescription>Candidate full name.</FormDescription>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- Manager -->
    <FormField name="manager_id" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Manager</FormLabel>
        <FormControl>
          <Select v-bind="componentField">
            <SelectTrigger class="w-full"><SelectValue placeholder="Select a manager" /></SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem v-for="manager in managers" :key="manager.id" :value="manager.id">
                  {{ manager.name }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- Position -->
    <FormField name="position_id" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Position</FormLabel>
        <FormControl>
          <Select v-bind="componentField">
            <SelectTrigger class="w-full"><SelectValue placeholder="Select a position" /></SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem v-for="position in positions" :key="position.id" :value="position.id">
                  {{ position.name }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- Status -->
    <FormField name="status" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Status</FormLabel>
        <FormControl>
          <Select v-bind="componentField">
            <SelectTrigger class="w-full"><SelectValue placeholder="Select a status" /></SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem v-for="status in statusOptions" :key="status.value" :value="status.value">
                  {{ status.label }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- Days Required -->
    <FormField name="days_required" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Days Required</FormLabel>
        <FormControl>
          <Input type="number" v-bind="componentField" />
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- Dates and CV upload (similar to your create form) -->
    <FormField name="proposed_date" v-slot="{ }">
      <FormItem>
        <FormLabel>Proposed Date</FormLabel>
        <FormControl>
          <Popover>
            <PopoverTrigger as-child>
              <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !proposedDateValue && 'text-muted-foreground')">
                {{ proposedDateValue ? df.format(proposedDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :modelValue="proposedDateValue"
                @update:modelValue="proposedDateValue = $event"
              />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- CV Review Date -->
    <FormField name="cv_review_date" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>CV Review Date</FormLabel>
        <FormControl>
          <Popover>
            <PopoverTrigger as-child>
              <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !cvReviewDateValue && 'text-muted-foreground')">
                {{ cvReviewDateValue ? df.format(cvReviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-bind="componentField" v-model="cvReviewDateValue" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- HR Interview Date -->
    <FormField name="hr_interview_date" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>HR Interview Date</FormLabel>
        <FormControl>
          <Popover>
            <PopoverTrigger as-child>
              <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !hrInterviewDateValue && 'text-muted-foreground')">
                {{ hrInterviewDateValue ? df.format(hrInterviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-bind="componentField" v-model="hrInterviewDateValue" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- CV Upload -->
    <FormField name="cv" v-slot="{ componentField }">
      <FormItem>
        <FormLabel>Upload CV</FormLabel>
        <FormControl>
          <Input type="file" v-bind="componentField" @change="onFileChange" />
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <Button type="submit">Submit</Button>
  </form>
</template>
