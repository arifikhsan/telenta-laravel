<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { getStatusLabel } from '@/lib/candidate-util';
import { formatStandardDate } from '@/lib/date-util';
import { CandidateEntity } from '@/types/entity/candidate-entity';
import { ManagerCandidateRequestEntity } from '@/types/entity/manager-candidate-request-entity.d copy';
import { Head, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
  managerCandidateRequest: ManagerCandidateRequestEntity;
  unassignedCandidates: Array<CandidateEntity>;
  assignedCandidates: Array<CandidateEntity>;
}>();

function acceptRequest() {
  // call post on this route
  router.post(
    route('dashboard.manager-candidate-requests.accept', props.managerCandidateRequest.id),
    {},
    {
      onSuccess: () => {
        // Handle success
        toast.success('Request accepted successfully');
      },
      onError: () => {
        // Handle error
        toast.error('Failed to accept request');
      },
    },
  );
}

function rejectRequest() {
  // call post on this route
  router.post(
    route('dashboard.manager-candidate-requests.reject', props.managerCandidateRequest.id),
    {},
    {
      onSuccess: () => {
        // Handle success
        toast.success('Request rejected successfully');
      },
      onError: () => {
        // Handle error
        toast.error('Failed to reject request');
      },
    },
  );
}

// make a function that post to route here dashboard.candidate-request-fills.add-candidate
// and bring field candidate_id, and manager_request_id
function addCandidate(candidateId: number, managerCandidateRequestId: number) {
  router.post(
    route('dashboard.candidate-request-fills.add-candidate'),
    {
      candidate_id: candidateId,
      manager_candidate_request_id: managerCandidateRequestId,
    },
    {
      onSuccess: () => {
        // Handle success
        toast.success('Candidate added successfully');
      },
      onError: () => {
        // Handle error
        toast.error('Failed to add candidate');
      },
    },
  );
}

function removeCandidate(candidateId: number, managerCandidateRequestId: number) {
  router.post(
    route('dashboard.candidate-request-fills.remove-candidate'),
    {
      candidate_id: candidateId,
      manager_candidate_request_id: managerCandidateRequestId,
    },
    {
      onSuccess: () => {
        // Handle success
        toast.success('Candidate removed successfully');
      },
      onError: () => {
        // Handle error
        toast.error('Failed to remove candidate');
      },
    },
  );
}
</script>

<template>
  <Head title="Fulfill Manager Candidate Request" />
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="">
        <div>
          <h1>Fulfill Manager Candidate Request</h1>
          <p>Request ID: {{ props.managerCandidateRequest.id }}</p>
          <p>Manager: {{ props.managerCandidateRequest.manager.name }}</p>
          <p>Requested Count: {{ props.managerCandidateRequest.requested_count }}</p>
          <p>Fulfilled Count: {{ props.managerCandidateRequest.fulfilled_count }}</p>
          <p>
            Status: <Badge>{{ props.managerCandidateRequest.status }}</Badge>
          </p>
          <p>Date Requested: {{ formatStandardDate(props.managerCandidateRequest.date_requested) }}</p>
        </div>
        <div class="mt-4 flex gap-2" v-if="props.managerCandidateRequest.status === 'pending'">
          <Button @click="acceptRequest">Terima</Button>
          <Button variant="destructive" @click="rejectRequest">Tolak</Button>
        </div>
        <div class="mt-8">
          <h2 class="mb-2 text-lg font-semibold">Assigned Candidates</h2>
          <div v-if="!props.assignedCandidates || props.assignedCandidates.length === 0">No assigned candidates available.</div>
          <div v-else class="grid grid-cols-3 gap-4">
            <Card v-for="candidate in props.assignedCandidates" :key="candidate.id" class="w-full">
              <CardHeader>
                <CardTitle>{{ candidate.name }}</CardTitle>
                <CardDescription>{{ candidate.position?.name || 'N/A' }}</CardDescription>
              </CardHeader>
              <CardContent>
                <span class="text-sm">Status:</span> <Badge variant="outline">{{ getStatusLabel(candidate.status) }}</Badge>
              </CardContent>
              <CardFooter class="flex justify-between px-6">
                <Button variant="outline" @click="removeCandidate(candidate.id, props.managerCandidateRequest.id)">Keluarkan</Button>
              </CardFooter>
            </Card>
          </div>
        </div>
        <div class="mt-8" v-if="props.managerCandidateRequest.status === 'accepted'">
          <h2 class="mb-2 text-lg font-semibold">Unassigned Candidates</h2>
          <div v-if="!props.unassignedCandidates || props.unassignedCandidates.length === 0">No unassigned candidates available.</div>
          <div v-else class="grid grid-cols-3 gap-4">
            <Card v-for="candidate in props.unassignedCandidates" :key="candidate.id" class="w-full">
              <CardHeader>
                <CardTitle>{{ candidate.name }}</CardTitle>
                <CardDescription>{{ candidate.position?.name || 'N/A' }}</CardDescription>
              </CardHeader>
              <CardContent>
                <span class="text-sm">Status:</span> <Badge variant="outline">{{ getStatusLabel(candidate.status) }}</Badge>
              </CardContent>
              <CardFooter class="flex justify-between px-6">
                <Button variant="outline" @click="addCandidate(candidate.id, props.managerCandidateRequest.id)">Tambahkan</Button>
              </CardFooter>
            </Card>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
