<template>
    <BaseDialog
        ref="dialog"
        title="Submit Manuscript for Management Review"
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
            >
                <q-separator />
                <q-step
                    :name="1"
                    title="Confirm Submission"
                    icon="mdi-check"
                    :done="step > 1"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        Submission for Management Review
                    </div>
                    <p class="q-ma-md">
                        I certify that, to the best of my knowledge, this
                        manuscript is in compliance with the Intellectual
                        Property Policy/Copyright Act, the Privacy Act, the
                        Financial Administration Act (with respect to approvals
                        of publication costs), and the Values and Ethics Code
                        for DFO.
                    </p>
                    <q-option-group
                        v-model="agreeToTerms"
                        class="q-mr-lg"
                        align="right"
                        :options="agreeToTermsOptions"
                        inline
                    />
                </q-step>
                <q-step
                    :name="2"
                    title="Select Reviewer"
                    icon="mdi-account-search"
                    :error="validationError"
                    style="min-height: 275px"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        Division Manager/Director
                    </div>
                    <p class="q-ma-md">
                        To submit the manuscript for management review, search
                        for the appropriate division manager/director within the
                        user list. If you cannot find their name, they are
                        likely not yet registered, and you will be able to
                        invite them to the portal.
                    </p>
                    <p class="q-ma-md">
                        Note; you will not be able to select someone that is an
                        author on this manuscript or the owner of this
                        manuscript record.
                    </p>
                    <UserSelect
                        v-model="divisionManagerUserId"
                        label="Division Manager"
                        class="q-ma-md"
                        :disabled-emails="authorEmails"
                        :disabled-ids="[ownerId]"
                        :rules="[(val: number|null) => val !== null || 'Division Manager is required']"
                    />
                </q-step>
                <template #navigation>
                    <q-stepper-navigation class="flex justify-end">
                        <q-btn
                            color="primary"
                            :label="step === 2 ? 'Submit' : 'Next'"
                            :disable="!agreeToTerms"
                            :loading="loading"
                            @click="step === 2 ? submit() : stepper?.next()"
                        />
                        <q-btn v-close-popup flat label="Cancel" />
                    </q-stepper-navigation>
                </template>
            </q-stepper>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import { extractErrorMessages } from '@/api/errors';
import BaseDialog from '@/components/BaseDialog.vue';
import { ManuscriptAuthorService } from '@/models/ManuscriptAuthor/ManuscriptAuthor';
import UserSelect from '@/models/User/components/UserSelect.vue';
import { QDialog, QForm, QStepper } from 'quasar';
import { Ref } from 'vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const props = defineProps<{
    manuscriptRecordId: number;
}>();

const emit = defineEmits<{
    (event: 'submitted', arg: ManuscriptRecordResource): void;
}>();

const dialog: Ref<QDialog | null> = ref(null);
const form: Ref<QForm | null> = ref(null);
const stepper: Ref<QStepper | null> = ref(null);
const step = ref(1);
const validationError = ref(false);

const authorEmails = ref<string[]>([]);
const ownerId = ref<number>(0);

const divisionManagerUserId = ref<number | null>(null);
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

onMounted(async () => {
    authorEmails.value = await getAllManuscriptAuthorsEmails();
    ownerId.value = await getManuscriptOwnerId();
});

async function getAllManuscriptAuthorsEmails(): Promise<string[]> {
    const manuscriptAuthors = await ManuscriptAuthorService.list(
        props.manuscriptRecordId
    );
    return manuscriptAuthors.data.map((author) => author.data.author?.email);
}

async function getManuscriptOwnerId(): Promise<number> {
    const mansuscriptRecord = await ManuscriptRecordService.find(
        props.manuscriptRecordId
    );
    return mansuscriptRecord.data.user_id;
}

const loading = ref(false);
async function submit() {
    if (await form.value?.validate()) {
        loading.value = true;

        if (divisionManagerUserId?.value === null) return;

        const manuscriptRecord = await ManuscriptRecordService.submitForReview(
            props.manuscriptRecordId,
            divisionManagerUserId.value
        );

        loading.value = false;
        emit('submitted', manuscriptRecord);
    }
}
</script>

<style scoped></style>
