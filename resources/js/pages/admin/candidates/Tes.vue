<script setup lang="ts">
import { CandidateEntity } from '@/types/entity/candidate-entity';

defineProps({
  candidate: {
    type: Object as () => CandidateEntity,
    required: true,
  },
});
</script>

<template>
  <div>
    <h1>{{ candidate.name }}</h1>
    <p>Manager: {{ candidate.manager_id }}</p>
    <p>Position: {{ candidate.position_id }}</p>
    <p>Status: {{ candidate.status }}</p>
    <p>Days Required: {{ candidate.days_required }}</p>
    <p>Proposed Date: {{ candidate.proposed_date }}</p>
    <p>CV Review Date: {{ candidate.cv_review_date }}</p>
    <p>HR Interview Date: {{ candidate.hr_interview_date }}</p>
  </div>
</template>
