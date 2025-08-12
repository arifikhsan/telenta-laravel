<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import * as z from 'zod';

import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { DateFormatter, DateValue, getLocalTimeZone } from '@internationalized/date';
import axios from 'axios';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { PropType, ref } from 'vue';
import { toast } from 'vue-sonner';

// import { Inertia } from 'inertiajs/inertia-laravel'; // Import Inertia

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(50),
        manager_id: z.number().min(1, 'Manager is required'), // Convert to number validation
        position_id: z.number().min(1, 'Position is required'), // Convert to number validation
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
            .optional(),
    }),
);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: 'Sucipto',
        manager_id: 2,
        position_id: 1,
        status: 'cv_reviewed',
        days_required: 10,
    },
});

const onSubmit = form.handleSubmit(async (values) => {
    console.log('Form submitted!', values);

    const formData = new FormData();
    formData.append('name', values.name);

    formData.append('manager_id', values.manager_id.toString());
    formData.append('position_id', values.position_id.toString());

    formData.append('status', values.status);
    formData.append('days_required', values.days_required.toString());

    if (values.proposed_date) {
        formData.append('proposed_date', values.proposed_date?.toDate(getLocalTimeZone())?.toISOString() || '');
    }
    if (values.cv_review_date) {
        formData.append('cv_review_date', values.cv_review_date?.toDate(getLocalTimeZone())?.toISOString() || '');
    }
    if (values.hr_interview_date) {
        formData.append('hr_interview_date', values.hr_interview_date?.toDate(getLocalTimeZone())?.toISOString() || '');
    }

    if (values.cv) {
        formData.append('cv', values.cv);
    }

    try {
        const response = await axios.post('/dashboard/candidates/store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        console.log('Form submitted successfully!');
        const message = response.data.message;
        toast.success(message);
        setTimeout(() => {
            window.location.href = '/dashboard/candidates';
        }, 2000); // Adjust the delay if needed
    } catch (error: any) {
        toast.error('Error submitting form:', {description: error.toString()});
    }
});

const props = defineProps({
    managers: {
        type: Array as PropType<Array<{ id: number; name: string }>>, // Define the prop structure
        required: true,
    },
    positions: {
        type: Array as PropType<Array<{ id: number; name: string }>>, // Define the prop structure
        required: true,
    },
});

const statusOptions = [
    { value: 'cv_reviewed', label: 'CV Reviewed' },
    { value: 'hr_interviewed', label: 'HR Interviewed' },
    { value: 'hired', label: 'Hired' },
];

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
});

const proposedDateValue = ref<DateValue>();
const cvReviewDateValue = ref<DateValue>();
const hrInterviewDateValue = ref<DateValue>();
const cvFile = ref<File | null>(null);

const onFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files?.[0]) {
        cvFile.value = input.files[0];
    }
};
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="max-w-sm">
                <form @submit="onSubmit" class="space-y-6">
                    <FormField v-slot="{ componentField }" name="name">
                        <FormItem>
                            <FormLabel>Name</FormLabel>
                            <FormControl>
                                <Input type="text" placeholder="Sucipto" v-bind="componentField" />
                            </FormControl>
                            <FormDescription> Candidate full name. </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="manager_id">
                        <FormItem>
                            <FormLabel>Manager</FormLabel>
                            <FormControl>
                                <Select v-bind="componentField">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a manager" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="manager in props.managers" :key="manager.id" :value="manager.id">
                                                {{ manager.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="position_id">
                        <FormItem>
                            <FormLabel>Position</FormLabel>
                            <FormControl>
                                <Select v-bind="componentField">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a position" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="position in props.positions" :key="position.id" :value="position.id">
                                                {{ position.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="status">
                        <FormItem>
                            <FormLabel>Status</FormLabel>
                            <FormControl>
                                <Select v-bind="componentField">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a status" />
                                    </SelectTrigger>
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
                    <FormField v-slot="{ componentField }" name="days_required">
                        <FormItem>
                            <FormLabel>Days Required</FormLabel>
                            <FormControl>
                                <Input type="number" placeholder="10" v-bind="componentField" />
                            </FormControl>
                            <FormDescription> Days required to hire the candidate. </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="proposed_date">
                        <FormItem>
                            <FormLabel>Proposed Date</FormLabel>
                            <FormControl>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button
                                            variant="outline"
                                            :class="cn('w-full justify-start text-left font-normal', !proposedDateValue && 'text-muted-foreground')"
                                        >
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ proposedDateValue ? df.format(proposedDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar v-bind="componentField" v-model="proposedDateValue" initial-focus />
                                    </PopoverContent>
                                </Popover>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="cv_review_date">
                        <FormItem>
                            <FormLabel>CV Review Date</FormLabel>
                            <FormControl>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button
                                            variant="outline"
                                            :class="cn('w-full justify-start text-left font-normal', !cvReviewDateValue && 'text-muted-foreground')"
                                        >
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ cvReviewDateValue ? df.format(cvReviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar v-bind="componentField" v-model="cvReviewDateValue" initial-focus />
                                    </PopoverContent>
                                </Popover>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="hr_interview_date">
                        <FormItem>
                            <FormLabel>HR Review Date</FormLabel>
                            <FormControl>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button
                                            variant="outline"
                                            :class="
                                                cn('w-full justify-start text-left font-normal', !hrInterviewDateValue && 'text-muted-foreground')
                                            "
                                        >
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ hrInterviewDateValue ? df.format(hrInterviewDateValue.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar v-bind="componentField" v-model="hrInterviewDateValue" initial-focus />
                                    </PopoverContent>
                                </Popover>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <FormField v-slot="{ componentField }" name="cv">
                        <FormItem>
                            <FormLabel>Upload CV (Optional)</FormLabel>
                            <FormControl>
                                <Input type="file" accept="application/pdf, .docx, .doc" v-bind="componentField" @change="onFileChange" />
                            </FormControl>
                            <FormDescription> Upload the candidate's CV (optional). </FormDescription>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <Button type="submit"> Submit </Button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
