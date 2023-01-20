<template>
    <BaseDialog
        ref="dialog"
        :title="$t('review-step.submit-decision')"
        persistent
    >
        <q-form
            ref="form"
            @validation-error="validationError = true"
            @validation-success="validationError = false"
        >
            <q-stepper
                ref="stepper"
                v-model="step"
                color="primary"
                animated
                flat
                bordered
                keep-alive
            >
                <q-separator />
                <q-step
                    :name="1"
                    :title="$t('submit-decision-dialog.your-decision')"
                    icon="mdi-check"
                    :done="step > 1"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        {{ $t('submit-decision-dialog.select-decision') }}
                    </div>
                    <q-list>
                        <q-item
                            v-for="option in options"
                            :key="option.value"
                            v-ripple
                            tag="label"
                            :disable="option.disabled"
                        >
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    :val="option.value"
                                    :disable="option.disabled"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1">{{
                                    option.label
                                }}</q-item-label>
                                <q-item-label class="text-body2 text-grey-8"
                                    >{{ option.description }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-step>
                <q-step
                    :name="2"
                    :title="$t('submit-decision-dialog.select-next-reviewer')"
                    icon="mdi-account-search"
                    :error="validationError"
                    style="min-height: 275px"
                    :disable="nextReviewerStepDisabled"
                    :done="step > 2"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        {{ $t('submit-decision-dialog.next-reviewer') }}
                    </div>
                    <p class="q-ma-md">
                        {{ $t('submit-decision-dialog.next-reviewer-hint') }}
                    </p>
                    <UserSelect
                        v-model="nextUserId"
                        :label="$t('submit-decision-dialog.next-reviewer')"
                        class="q-ma-md"
                        :disabled-emails="authorEmails"
                        :disabled-ids="[ownerId, managementReviewStep.user_id]"
                        :rules="[(val: number|null) => val !== null || $t('submit-decision-dialog.subsequent-reviewer-is-required')]"
                    />
                </q-step>
                <q-step
                    :name="3"
                    :title="$t('submit-decision-dialog.confirm-decision')"
                    icon="mdi-check-decagram"
                    :done="step > 3"
                >
                    <p class="q-ma-md">
                        {{ $t('submit-decision-dialog.certify-text') }}
                    </p>
                    <q-option-group
                        v-model="agreeToTerms"
                        class="q-mr-lg"
                        align="right"
                        :options="agreeToTermsOptions"
                        inline
                    />
                </q-step>
                <template #navigation>
                    <q-stepper-navigation class="flex justify-end">
                        <q-btn
                            v-if="step > 1"
                            color="primary"
                            :label="$t('common.back')"
                            class="q-mr-sm"
                            outline
                            @click="stepper?.previous()"
                        />
                        <q-btn
                            color="primary"
                            :label="
                                step === 3
                                    ? $t('common.submit')
                                    : $t('common.next')
                            "
                            :loading="loading"
                            :disable="nextDisabled"
                            @click="step === 3 ? submit() : stepper?.next()"
                        />
                        <q-btn
                            v-close-popup
                            flat
                            :label="$t('common.cancel')"
                        />
                    </q-stepper-navigation>
                </template>
                <template #message>
                    <q-banner
                        v-if="step === 1 && stepHasNoComments"
                        class="bg-primary text-white"
                    >
                        <template #avatar>
                            <q-icon
                                name="mdi-comment-alert-outline"
                                size="md"
                            />
                        </template>
                        {{ $t('submit-decision-dialog.no-comments-warning') }}
                    </q-banner>
                </template>
            </q-stepper>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { ManuscriptAuthorService } from '@/models/ManuscriptAuthor/ManuscriptAuthor';
import { ManuscriptRecordService } from '@/models/ManuscriptRecord/ManuscriptRecord';
import UserSelect from '@/models/User/components/UserSelect.vue';
import { QDialog, QForm, QStepper } from 'quasar';
import { Ref } from 'vue';
import {
    ManagementReviewStep,
    ManagementReviewStepResource,
    ManagementReviewStepService,
} from '../ManagementReviewStep';

const authStore = useAuthStore();
const { t } = useI18n();

const props = defineProps<{
    managementReviewStep: ManagementReviewStep;
}>();

const emit = defineEmits<{
    (event: 'decision', arg: ManagementReviewStepResource): void;
}>();

const dialog: Ref<QDialog | null> = ref(null);
const form: Ref<QForm | null> = ref(null);
const stepper: Ref<QStepper | null> = ref(null);
const step = ref(1);
const validationError = ref(false);

type Decision =
    | 'approveAndComplete'
    | 'approveAndForward'
    | 'withholdAndComplete'
    | 'withholdAndForward'
    | 'reassign';

type DecisionOption = {
    label: string;
    value: Decision;
    description: string;
    disabled: boolean;
};

const decision: Ref<Decision> = ref('approveAndComplete');
const nextUserId = ref<number | null>(null);

/** Decision flow variables */
const agreeToTerms = ref(false);
const agreeToTermsOptions = ref([
    {
        label: t('common.yes'),
        value: true,
    },
    {
        label: t('common.no'),
        value: false,
    },
]);

const nextReviewerStepDisabled = computed(() => {
    return (
        decision.value === 'approveAndComplete' ||
        decision.value === 'withholdAndComplete'
    );
});

const nextDisabled = computed(() => {
    if (step.value === 1) {
        return false;
    } else if (step.value === 2) {
        return nextUserId.value === null;
    } else if (step.value === 3) {
        return !agreeToTerms.value;
    }
});

/**
 * This is used to determine which options are available to the user.
 */
const stepHasNoComments = computed(() => {
    return props.managementReviewStep.comments.length === 0;
});

/**
 * Checks that the user has permission to withhold a manuscript.
 */
const userCanWithholdAndComplete = computed(() => {
    return (
        authStore.user?.can('withhold_and_complete_management_review') ?? false
    );
});

/**
 * The options available to the user for their decision.
 */
const options = ref<DecisionOption[]>([
    {
        label: t('decision.approve-and-complete'),
        value: 'approveAndComplete',
        description: t('decision.approve-and-complete-desc'),
        disabled: false,
    },
    {
        label: t('decision.approve-and-forward'),
        value: 'approveAndForward',
        description: t('decision.approve-and-forward-desc'),
        disabled: stepHasNoComments.value,
    },
    {
        label: t('decision.withhold-and-complete'),
        value: 'withholdAndComplete',
        description: t('decision.withhold-and-complete-desc'),
        disabled: stepHasNoComments.value || !userCanWithholdAndComplete.value,
    },
    {
        label: t('decision.withhold-and-forward'),
        value: 'withholdAndForward',
        description: t('decision.withhold-and-forward-desc'),
        disabled: stepHasNoComments.value,
    },
    {
        label: t('decision.reassign'),
        value: 'reassign',
        description: t('decision.reassign-desc'),
        disabled: stepHasNoComments.value,
    },
]);

/**
 * Variables used to ensure that the next reviewer is not the author or the
 * owner of the manuscript.
 */
const authorEmails = ref<string[]>([]);
const ownerId = ref<number>(0);

onMounted(async () => {
    authorEmails.value = await getAllManuscriptAuthorsEmails();
    ownerId.value = await getManuscriptOwnerId();
});

async function getAllManuscriptAuthorsEmails(): Promise<string[]> {
    const manuscriptAuthors = await ManuscriptAuthorService.list(
        props.managementReviewStep.manuscript_record_id
    );
    return manuscriptAuthors.data.map(
        (manuscriptAuthor) => manuscriptAuthor.data.author?.data.email ?? ''
    );
}

async function getManuscriptOwnerId(): Promise<number> {
    const mansuscriptRecord = await ManuscriptRecordService.find(
        props.managementReviewStep.manuscript_record_id
    );
    return mansuscriptRecord.data.user_id;
}

/**
 * Submits the decision to the API.
 */
const loading = ref(false);
async function submit() {
    var response: ManagementReviewStepResource | null = null;
    switch (decision.value) {
        case 'approveAndComplete':
            response = await ManagementReviewStepService.approve(
                props.managementReviewStep
            );
            break;
        case 'approveAndForward':
            if (nextUserId.value === null) {
                throw new Error('nextUserId is null');
            }
            response = await ManagementReviewStepService.approve(
                props.managementReviewStep,
                nextUserId.value
            );
            break;
        case 'withholdAndComplete':
            if (!userCanWithholdAndComplete.value) {
                throw new Error('User cannot withhold and complete');
            }
            response = await ManagementReviewStepService.withhold(
                props.managementReviewStep
            );
            break;
        case 'withholdAndForward':
            if (nextUserId.value === null) {
                throw new Error('nextUserId is null');
            }
            response = await ManagementReviewStepService.withhold(
                props.managementReviewStep,
                nextUserId.value
            );
            break;
        case 'reassign':
            if (nextUserId.value === null) {
                throw new Error('nextUserId is null');
            }
            response = await ManagementReviewStepService.reassign(
                props.managementReviewStep,
                nextUserId.value
            );
            break;
    }
    if (response === null) return;
    emit('decision', response);
}
</script>

<style scoped></style>
