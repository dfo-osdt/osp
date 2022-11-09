<template>
    <q-list bordered separator>
        <q-item
            v-for="step in managementReviewSteps"
            :key="step.data.id"
            clickable
            :to="{
                name: 'manuscript.reviews',
                params: { id: step.data.manuscript_record_id },
            }"
        >
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-accent"
                    >{{
                        step.data.manuscript_record?.data.title ?? 'No title'
                    }}</q-item-label
                >
                <q-item-label caption>
                    Review request received from
                    {{ userName(step) }} on
                    {{ useLocaleDate(step.data.created_at).value }}
                </q-item-label>
            </q-item-section>
            <q-item-section side top>
                <q-item-label class="q-mt-sm">
                    <q-badge outline color="primary" class="text-body2 q-mr-sm">
                        <ManagementReviewStepStatusSpan
                            :status="step.data.status"
                        />
                    </q-badge>
                    <q-badge outline color="primary" class="text-body2">
                        <ManagementReviewStepDecisionSpan
                            :decision="step.data.decision"
                        />
                    </q-badge>
                </q-item-label>
            </q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import { ManagementReviewStepResource } from '../ManagementReviewStep';
import ManagementReviewStepStatusSpan from './ManagementReviewStepStatusSpan.vue';
import ManagementReviewStepDecisionSpan from './ManagementReviewStepDecisionSpan.vue';
import { useLocaleDate } from '@/composables/useLocaleDate';

defineProps<{
    managementReviewSteps: ManagementReviewStepResource[];
}>();

const userName = (step: ManagementReviewStepResource) => {
    let user = null;
    if (step.data.previous_step_id === null) {
        user = step.data.manuscript_record?.data.user;
    } else {
        user = step.data.previous_step?.data.user;
    }
    return user ? `${user.data.first_name} ${user.data.last_name}` : 'Unknown';
};
</script>

<style scoped></style>
