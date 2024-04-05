<template>
    <q-timeline-entry class="q-mx-lg" :icon="icon" :color="color">
        <template v-if="canUpdate" #title>{{
            managementStep.data.status === 'on_hold'
                ? $t('review-step.your-response', [userName])
                : $t('review-step.your-review', [userName])
        }}</template>
        <template v-else #title>
            {{
                managementStep.data.status == 'on_hold'
                    ? $t('review-step.response-by', [userName])
                    : $t('review-step.review-by', [userName])
            }}</template
        >
        <template #subtitle>
            <ManagementReviewStepStatusSpan
                :status="managementStep.data.status"
            />
            <span v-if="completedAtDate"> - {{ completedAtDate }}</span>
        </template>
        <template v-if="canUpdate">
            <q-card
                v-if="managementStep.data.status === 'on_hold'"
                class="q-pa-md"
                bordered
                flat
            >
                <div class="text-body1 text-weight-medium text-accent">
                    {{ $t('management-review-response.title') }}
                </div>
                <p>
                    {{ $t('management-review-response.subtitle') }}
                </p>
                <question-editor
                    v-model="managementStep.data.comments"
                    :title="$t('author-comments.title')"
                >
                    <p>
                        {{ $t('author-comments.description') }}
                    </p>
                </question-editor>
                <q-card-actions align="right">
                    <q-btn
                        color="primary"
                        outline
                        :label="$t('review-step.save-comments')"
                        @click="save"
                    />
                </q-card-actions>
            </q-card>
            <q-card v-else class="q-pa-md" bordered flat>
                <div class="text-body1 text-weight-medium text-accent">
                    {{ $t('management-review-guidelines.title') }}
                </div>
                <p>
                    {{ $t('management-review-guidelines.subtitle') }}
                </p>
                <ul class="q-mb-md">
                    <li>
                        {{ $t('management-review-guidelines.tip-one') }}
                    </li>
                    <li>
                        {{ $t('management-review-guidelines.tip-two') }}
                    </li>
                    <li>
                        {{ $t('management-review-guidelines.tip-three') }}
                    </li>
                </ul>
                <question-editor
                    v-model="managementStep.data.comments"
                    :title="$t('reviewer-comments.title')"
                >
                    <p>
                        {{ $t('reviewer-comments.description') }}
                    </p>
                </question-editor>
                <q-card-actions align="right">
                    <q-btn
                        color="primary"
                        outline
                        :label="$t('review-step.save-comments')"
                        @click="save"
                    />
                    <q-btn
                        icon="mdi-arrow-decision"
                        color="primary"
                        :label="$t('review-step.submit-decision')"
                        @click="showDecisionDialog"
                    />
                    <SubmitDecisionDialog
                        v-if="submitDecisionDialog"
                        v-model="submitDecisionDialog"
                        :management-review-step="managementStep.data"
                        @decision="decisionSubmitted"
                    />
                </q-card-actions>
            </q-card>
        </template>
        <template v-else>
            <q-card
                v-if="
                    managementStep.data.status !== 'pending' &&
                    managementStep.data.status !== 'on_hold'
                "
                bordered
                flat
                class="bg-white q-pa-md"
            >
                <div
                    v-if="managementStep.data.decision !== 'none'"
                    class="q-mb-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-grey-8 q-mr-md"
                        >{{ $t('common.decision') }}</span
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
                        {{ $t('common.comments') }}
                    </div>
                    <span class="text-grey-8">
                        <!-- eslint-disable-next-line vue/no-v-html -->
                        <div v-html="safeComments" />
                    </span>
                </div>
            </q-card>
        </template>
        <WarnOnUnsavedChanges :is-dirty="isDirty" />
    </q-timeline-entry>
</template>

<script setup lang="ts">
import {
    ManagementReviewStepResource,
    ManagementReviewStepService,
} from '../ManagementReviewStep';
import ManagementReviewStepStatusSpan from '../components/ManagementReviewStepStatusSpan.vue';
import QuestionEditor from '@/components/QuestionEditor.vue';
import ManagementReviewStepDecisionSpan from './ManagementReviewStepDecisionSpan.vue';
import DOMPurify from 'dompurify';
import { useLocaleDate } from '@/composables/useLocaleDate';
import { useQuasar } from 'quasar';
import SubmitDecisionDialog from './SubmitDecisionDialog.vue';
import WarnOnUnsavedChanges from '@/components/WarnOnUnsavedChanges.vue';
import { use } from 'chai';

const { t } = useI18n();
const $q = useQuasar();

const props = defineProps<{
    modelValue: ManagementReviewStepResource;
}>();
const emit = defineEmits<{
    (event: 'update:modelValue', value: ManagementReviewStepResource): void;
    (event: 'decision', value: ManagementReviewStepResource): void;
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
        case 'on_hold':
            return 'mdi-timer-pause-outline';
        default:
            return 'mdi-account-question-outline';
    }
});

const color = computed(() => {
    switch (managementStep.value.data.status) {
        case 'completed':
            switch (managementStep.value.data.decision) {
                case 'approved':
                    return 'primary';
                case 'withheld':
                    return 'red';
                case 'flagged':
                    return 'red';
                case 'withdrawn':
                    return 'red';
                default:
                    return 'primary';
            }
        case 'pending':
        case 'on_hold':
            return 'yellow-8';
        case 'deferred':
            return 'secondary';
        default:
            return 'primary';
    }
});

const completedAtDate = useLocaleDate(managementStep.value.data.completed_at);

// The comments from the user but sanitize the HTML
const safeComments = computed(() => {
    return DOMPurify.sanitize(managementStep.value.data.comments);
});

const isDirty = ref(false);
watch(
    () => managementStep.value.data.comments,
    () => {
        isDirty.value = true;
    },
);

async function save() {
    await ManagementReviewStepService.update(managementStep.value.data);

    $q.notify({
        message: t('common.saved'),
        type: 'positive',
    });

    isDirty.value = false;
}

const submitDecisionDialog = ref(false);
async function showDecisionDialog() {
    await save();
    submitDecisionDialog.value = true;
}
function decisionSubmitted(decision: ManagementReviewStepResource) {
    console.log(decision);
    managementStep.value = decision;
    submitDecisionDialog.value = false;
    emit('decision', decision);
}
</script>

<style scoped></style>
