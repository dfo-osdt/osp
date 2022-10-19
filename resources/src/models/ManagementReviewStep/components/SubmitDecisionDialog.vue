<template>
    <BaseDialog ref="dialog" title="Submit Decision" persistent>
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
                    title="Your Decision"
                    icon="mdi-check"
                    :done="step > 1"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        Select your decision
                    </div>
                    <q-list>
                        <q-item v-ripple tag="label">
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    val="approveAndComplete"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1"
                                    >Approve and Complete</q-item-label
                                >
                                <q-item-label class="text-body2 text-grey-8"
                                    >You approve this manuscript for publication
                                    and are ending the management review
                                    process.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item
                            v-ripple
                            tag="label"
                            :disable="stepHasNoComments"
                        >
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    val="approveAndForward"
                                    :disable="stepHasNoComments"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1"
                                    >Approve and Forward</q-item-label
                                >
                                <q-item-label class="text-body2 text-grey-8"
                                    >You recommend approval of this manuscript
                                    for publication and are forwarding it to the
                                    next reviewer.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item
                            v-ripple
                            tag="label"
                            :disable="stepHasNoComments"
                        >
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    val="withholdAndComplete"
                                    :disable="stepHasNoComments"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1"
                                    >Withhold and Complete</q-item-label
                                >
                                <q-item-label class="text-body2 text-grey-8"
                                    >You withhold this manuscript for
                                    publication and are ending the management
                                    review process.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item
                            v-ripple
                            tag="label"
                            :disable="stepHasNoComments"
                        >
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    val="withholdAndForward"
                                    :disable="stepHasNoComments"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1"
                                    >Withhold and Forward</q-item-label
                                >
                                <q-item-label class="text-body2 text-grey-8"
                                    >You recommend this manuscript be withheld
                                    for publication and are forwarding it to the
                                    next reviewer.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item
                            v-ripple
                            tag="label"
                            :disable="stepHasNoComments"
                        >
                            <q-item-section avatar>
                                <q-radio
                                    v-model="decision"
                                    val="defer"
                                    :disable="stepHasNoComments"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-body1"
                                    >Defer</q-item-label
                                >
                                <q-item-label class="text-body2 text-grey-8"
                                    >You are not the right reviewer for this
                                    manuscript and defer this review to the
                                    proper reviewer without making a
                                    recommendation.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-step>
                <q-step
                    :name="2"
                    title="Select Next Reviewer"
                    icon="mdi-account-search"
                    :error="validationError"
                    style="min-height: 275px"
                    :disable="nextReviewerStepDisabled"
                    :done="step > 2"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        Next Reviewer
                    </div>
                    <p class="q-ma-md">
                        Search for the appropriate reviewer within the user
                        list. If you cannot find their name, they are likely not
                        yet registered, and you will be able to invite them to
                        the portal.
                    </p>
                    <UserSelect
                        v-model="nextUserId"
                        label="Next Reviewer"
                        class="q-ma-md"
                        :disabled-emails="authorEmails"
                        :disabled-ids="[ownerId, managementReviewStep.user_id]"
                        :rules="[(val: number|null) => val !== null || 'Given your decision, a subsequent reviewer is required']"
                    />
                </q-step>
                <q-step
                    :name="3"
                    title="Confirm Decision"
                    icon="mdi-check-decagram"
                    :done="step > 3"
                >
                    <p class="q-ma-md">
                        I certify that, to the best of my knowledge, my decision
                        is based on the guidelines outlined in the DFO National
                        Policy for Science publications. If applicable, I have
                        read the comments provided by the previous reviewer and
                        have considered them in my decision.
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
                            label="Back"
                            class="q-mr-sm"
                            outline
                            @click="stepper?.previous()"
                        />
                        <q-btn
                            color="primary"
                            :label="step === 3 ? 'Submit' : 'Next'"
                            :loading="loading"
                            :disable="nextDisabled"
                            @click="step === 3 ? submit() : stepper?.next()"
                        />
                        <q-btn v-close-popup flat label="Cancel" />
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
                        Your review has no comments. Therefore, you can only
                        approve and complete this review. Please add comments to
                        support your decision if you want to select from the
                        other options.
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

const authorEmails = ref<string[]>([]);
const ownerId = ref<number>(0);

type Decision =
    | 'approveAndComplete'
    | 'approveAndForward'
    | 'withholdAndComplete'
    | 'withholdAndForward'
    | 'defer';

const decision: Ref<Decision> = ref('approveAndComplete');
const nextUserId = ref<number | null>(null);
const agreeToTerms = ref(false);
const agreeToTermsOptions = ref([
    {
        label: 'Yes',
        value: true,
    },
    {
        label: 'No',
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

const stepHasNoComments = computed(() => {
    return props.managementReviewStep.comments.length === 0;
});

onMounted(async () => {
    authorEmails.value = await getAllManuscriptAuthorsEmails();
    ownerId.value = await getManuscriptOwnerId();
});

async function getAllManuscriptAuthorsEmails(): Promise<string[]> {
    const manuscriptAuthors = await ManuscriptAuthorService.list(
        props.managementReviewStep.manuscript_record_id
    );
    return manuscriptAuthors.data.map((author) => author.data.author?.email);
}

async function getManuscriptOwnerId(): Promise<number> {
    const mansuscriptRecord = await ManuscriptRecordService.find(
        props.managementReviewStep.manuscript_record_id
    );
    return mansuscriptRecord.data.user_id;
}

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
        case 'defer':
            if (nextUserId.value === null) {
                throw new Error('nextUserId is null');
            }
            response = await ManagementReviewStepService.defer(
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
