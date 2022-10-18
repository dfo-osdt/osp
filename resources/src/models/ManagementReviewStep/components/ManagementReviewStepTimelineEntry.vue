<template>
    <q-timeline-entry class="q-mx-lg" :icon="icon" :color="color">
        <template v-if="canUpdate" #title
            >Your Review : {{ userName }}</template
        >
        <template v-else #title>Review by {{ userName }}</template>
        <template #subtitle>
            <ManagementReviewStepStatusSpan
                :status="managementStep.data.status"
            />
            <div v-if="completedAtDate">- {{ completedAtDate }}</div>
        </template>
        <template v-if="canUpdate">
            <q-card class="q-pa-md" bordered flat>
                <div class="text-body1 text-weight-medium text-accent">
                    Management Review Guidelines
                </div>
                <p>
                    Please refer to the Fisheries and Oceans Canada National
                    Policy for Science Publications for more details.
                </p>
                <ul class="q-mb-md">
                    <li>
                        Reviews the manuscript for compliance with the
                        Intellectual Property Policy/Copyright Act, the Privacy
                        Act, the Financial Administration Act (with respect to
                        approvals of publication costs) and the Values and
                        Ethics Code for DFO.
                    </li>
                    <li>
                        Review the manuscript to identify sensitive issues,
                        solely for the purpose of briefing senior management and
                        the Communications Branch prior to publication of the
                        science paper. Identified sensitive issues are to be
                        added to the form's "Sensitive Issues" section.
                    </li>
                    <li>
                        At no time, will the inclusion of sensitive material
                        (e.g. data, scientific conclusions) prevent publication
                        of scientific papers.
                    </li>
                </ul>

                <question-editor
                    v-model="managementStep.data.comments"
                    title="Reviewer Comments"
                >
                    <p>
                        Comments entered here should support your decision,
                        provide feedback to the next reviewers and authors.
                        Comments will be visible to anyone with access to this
                        manuscript record. A comment is required unless you
                        approve and complete the management review without
                        sending to another reviewer.
                    </p>
                </question-editor>
            </q-card>
        </template>
        <template v-else>
            <div v-if="managementStep.data.decision !== 'none'" class="q-mb-md">
                <span
                    class="text-weight-bold text-uppercase text-grey-8 q-mr-md"
                    >Manuscript</span
                >
                <ManagementReviewStepDecisionSpan
                    class="text-weight-bold text-uppercase text-accent"
                    :class="`text-${color}`"
                    :decision="managementStep.data.decision"
                />
            </div>
            <div v-if="managementStep.data.comments !== ''">
                <div
                    class="text-weight-bold text-uppercase text-grey-8 q-mr-md"
                >
                    Comments
                </div>
                <span class="text-grey-8">
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <div v-html="safeComments" />
                </span>
            </div>
        </template>
    </q-timeline-entry>
</template>

<script setup lang="ts">
import { ManagementReviewStepResource } from '../ManagementReviewStep';
import ManagementReviewStepStatusSpan from '../components/ManagementReviewStepStatusSpan.vue';
import QuestionEditor from '@/components/QuestionEditor.vue';
import ManagementReviewStepDecisionSpan from './ManagementReviewStepDecisionSpan.vue';
import DOMPurify from 'dompurify';
import { useLocaleDate } from '@/composables/useLocaleDate';
const { t } = useI18n();

const props = defineProps<{
    modelValue: ManagementReviewStepResource;
}>();
const emit = defineEmits<{
    (event: 'update:modelValue', value: ManagementReviewStepResource): void;
}>();
const managementStep = useVModel(props, 'modelValue', emit);

const canUpdate = computed(() => {
    return managementStep.value.can?.update ? true : false;
});

const userName = computed(() => {
    if (!managementStep.value.data.user) {
        return t('common.unknown');
    }
    const { first_name, last_name } = managementStep.value.data.user?.data;
    return `${first_name} ${last_name}`;
});

const icon = computed(() => {
    switch (managementStep.value.data.status) {
        case 'completed':
            return 'mdi-account-check-outline';
        case 'pending':
            return 'mdi-account-clock-outline';
        case 'deferred':
            return 'mdi-account-arrow-right-outline';
        default:
            return 'mdi-account-question-outline';
    }
});

const color = computed(() => {
    switch (managementStep.value.data.status) {
        case 'completed':
            return managementStep.value.data.decision === 'approved'
                ? 'primary'
                : 'red';
        case 'pending':
            return 'orange';
        case 'deferred':
            return 'primary';
        default:
            return 'primary';
    }
});

const completedAtDate = useLocaleDate(managementStep.value.data.completed_at);

// The comments from the user but purify the HTML
const safeComments = computed(() => {
    return DOMPurify.sanitize(managementStep.value.data.comments);
});
</script>

<style scoped></style>
