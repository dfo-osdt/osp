<template>
    <q-list separator>
        <q-item
            v-for="step in managementReviewSteps"
            :key="step.data.id"
            clickable
            :to="{
                name: 'manuscript.reviews',
                params: { id: step.data.manuscript_record_id },
            }"
        >
            <q-item-section avatar>
                <q-icon
                    :name="
                        step.data.status === 'completed'
                            ? 'mdi-check-circle-outline'
                            : 'mdi-progress-check'
                    "
                    class="text-accent"
                    size="md"
                ></q-icon>
            </q-item-section>
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-accent"
                    >{{
                        step.data.manuscript_record?.data.title ??
                        $t('common.no-title')
                    }}</q-item-label
                >
                <q-item-label caption>
                    {{
                        $t(
                            'management-review-step-list.review-request-received-from',
                        )
                    }}
                    {{ userName(step) }} {{ $t('common.on') }}
                    {{ useLocaleDate(step.data.created_at).value }}
                </q-item-label>
            </q-item-section>
            <q-item-section side top>
                <q-item-label class="q-mt-sm">
                    <ManagementReviewStepStatusBadge
                        outline
                        :status="step.data.status"
                        class="text-body2 q-mr-sm"
                    />
                    <q-badge outline color="primary" class="text-body2">
                        <ManagementReviewStepDecisionSpan
                            :decision="step.data.decision"
                            show-label
                        />
                    </q-badge>
                </q-item-label>
            </q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import { ManagementReviewStepResource } from '../ManagementReviewStep';
import ManagementReviewStepDecisionSpan from './ManagementReviewStepDecisionSpan.vue';
import { useLocaleDate } from '@/composables/useLocaleDate';
import ManagementReviewStepStatusBadge from './ManagementReviewStepStatusBadge.vue';
const { t } = useI18n();

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
    return user
        ? `${user.data.first_name} ${user.data.last_name}`
        : t('common.unknown');
};
</script>

<style scoped></style>
