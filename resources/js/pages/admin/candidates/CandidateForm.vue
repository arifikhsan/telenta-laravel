<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { statusOptions } from '@/lib/candidate-util';
import { formatStandardDateFromDate } from '@/lib/date-util';
import { cn } from '@/lib/utils';
import { CandidateEntity } from '@/types/entity/candidate-entity';
import { ManagerEntity } from '@/types/entity/manager-entity';
import { PositionEntity } from '@/types/entity/position-entity';
import { CalendarDate, DateFormatter, DateValue, getLocalTimeZone } from '@internationalized/date';
import { toTypedSchema } from '@vee-validate/zod';
import { isEmpty } from 'lodash';
import { useForm } from 'vee-validate';
import { ref, watch } from 'vue';
import * as z from 'zod';

const props = defineProps({
  candidate: {
    type: Object as () => CandidateEntity,
    required: true,
  },
  managers: {
    type: Array as () => ManagerEntity[],
    required: true,
  },
  positions: {
    type: Array as () => PositionEntity[],
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
    proposed_date: z.custom<Date>((value) => value instanceof Date, { message: 'Invalid proposed date' }).optional(),
    cv_review_date: z.custom<Date>((value) => value instanceof Date, { message: 'Invalid cv review date' }).optional(),
    hr_interview_date: z.custom<Date>((value) => value instanceof Date, { message: 'Invalid hr review date' }).optional(),
    internal_interview_date: z.custom<Date>((value) => value instanceof Date, { message: 'Invalid internal interview date' }).optional(),
    user_interview_date: z.custom<Date>((value) => value instanceof Date, { message: 'Invalid user interview date' }).optional(),
    cv: z
      .instanceof(File) // Ensure it's a file
      .refine((file) => !file || file.size <= 10 * 1024 * 1024, 'CV file should be less than 10MB')
      .nullable(),
  }),
);

function dateToCalendarDate(d: Date | undefined): DateValue | undefined {
  console.log('d: ', d);
  if (!d) return undefined;
  if (typeof d === 'string') d = new Date(d);
  return new CalendarDate(d.getFullYear(), d.getMonth() + 1, d.getDate());
}

function calendarDateToDate(v: DateValue | undefined): Date | undefined {
  if (!v) return undefined;
  return v.toDate('UTC'); // or getLocalTimeZone() if you want local
}

const form = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.candidate?.name || '',
    manager_id: props.candidate ? props.candidate.manager_id : 0,
    position_id: props.candidate ? props.candidate.position_id : 0,
    status: props.candidate?.status || '',
    days_required: props.candidate?.days_required || 1,
    proposed_date: !isEmpty(props.candidate) ? new Date(props.candidate.proposed_date) : undefined,
    cv_review_date: !isEmpty(props.candidate) ? new Date(props.candidate.cv_review_date) : undefined,
    hr_interview_date: !isEmpty(props.candidate) ? new Date(props.candidate.hr_interview_date) : undefined,
    internal_interview_date: !isEmpty(props.candidate) ? new Date(props.candidate.internal_interview_date) : undefined,
    user_interview_date: !isEmpty(props.candidate) ? new Date(props.candidate.user_interview_date) : undefined,
    cv: null,
  },
});

const proposedDateValue = ref<DateValue | undefined>(dateToCalendarDate(form.values.proposed_date));
const cvReviewDateValue = ref<DateValue | undefined>(dateToCalendarDate(form.values.cv_review_date));
const hrInterviewDateValue = ref<DateValue | undefined>(dateToCalendarDate(form.values.hr_interview_date));
const internalInterviewDateValue = ref<DateValue | undefined>(dateToCalendarDate(form.values.internal_interview_date));
const userInterviewDateValue = ref<DateValue | undefined>(dateToCalendarDate(form.values.user_interview_date));
const cvFile = ref<File | null>(null);

watch(proposedDateValue, (val: any) => {
  form.setFieldValue('proposed_date', calendarDateToDate(val));
});
watch(cvReviewDateValue, (val: any) => {
  form.setFieldValue('cv_review_date', calendarDateToDate(val));
});
watch(hrInterviewDateValue, (val: any) => {
  form.setFieldValue('hr_interview_date', calendarDateToDate(val));
});
watch(internalInterviewDateValue, (val: any) => {
  form.setFieldValue('internal_interview_date', calendarDateToDate(val));
});
watch(userInterviewDateValue, (val: any) => {
  form.setFieldValue('user_interview_date', calendarDateToDate(val));
});
watch(cvFile, (val) => (form.values.cv = val));

const df = new DateFormatter('en-US', { dateStyle: 'long' });

const onFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (input.files?.[0]) cvFile.value = input.files[0];
};

const onSubmit = form.handleSubmit((values) => {
  const modifiedValues: Record<string, any> = { ...values };
  if (values.proposed_date instanceof Date && !isNaN(values.proposed_date.getTime())) {
    modifiedValues.proposed_date = formatStandardDateFromDate(values.proposed_date);
  }
  if (values.cv_review_date instanceof Date && !isNaN(values.cv_review_date.getTime())) {
    modifiedValues.cv_review_date = formatStandardDateFromDate(values.cv_review_date);
  }
  if (values.hr_interview_date instanceof Date && !isNaN(values.hr_interview_date.getTime())) {
    modifiedValues.hr_interview_date = formatStandardDateFromDate(values.hr_interview_date);
  }
  if (values.internal_interview_date instanceof Date && !isNaN(values.internal_interview_date.getTime())) {
    modifiedValues.internal_interview_date = formatStandardDateFromDate(values.internal_interview_date);
  }
  if (values.user_interview_date instanceof Date && !isNaN(values.user_interview_date.getTime())) {
    modifiedValues.user_interview_date = formatStandardDateFromDate(values.user_interview_date);
  }

  if (values.cv === null) {
    delete modifiedValues.cv;
  }
  emit('submit', modifiedValues);
});
</script>

<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
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
    <FormField name="proposed_date" v-slot="{}">
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
              <Calendar :modelValue="proposedDateValue as any" @update:modelValue="proposedDateValue = $event" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- CV Review Date -->
    <FormField name="cv_review_date" v-slot="{}">
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
              <Calendar v-model="cvReviewDateValue as any" @update:modelValue="cvReviewDateValue = $event" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <!-- HR Interview Date -->
    <FormField name="hr_interview_date" v-slot="{}">
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
              <Calendar v-model="hrInterviewDateValue as any" @update:modelValue="hrInterviewDateValue = $event" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <FormField name="internal_interview_date" v-slot="{}">
      <FormItem>
        <FormLabel>Internal Interview Date</FormLabel>
        <FormControl>
          <Popover>
            <PopoverTrigger as-child>
              <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !internalInterviewDateValue && 'text-muted-foreground')">
                {{ internalInterviewDateValue ? df.format(internalInterviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-model="internalInterviewDateValue as any" @update:modelValue="internalInterviewDateValue = $event" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <FormField name="user_interview_date" v-slot="{}">
      <FormItem>
        <FormLabel>User Interview Date</FormLabel>
        <FormControl>
          <Popover>
            <PopoverTrigger as-child>
              <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !userInterviewDateValue && 'text-muted-foreground')">
                {{ userInterviewDateValue ? df.format(userInterviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-model="userInterviewDateValue as any" @update:modelValue="userInterviewDateValue = $event" />
            </PopoverContent>
          </Popover>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <div v-if="props.candidate.cv_path" class="flex flex-col gap-2">
      <label>Current CV:</label>
      <a :href="props.candidate.cv_url" target="_blank" type="button">⬇️ Download current CV ⬇️</a>
    </div>

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
