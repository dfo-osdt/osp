<template>
    <BaseDialog
        ref="dialog"
        title="$t('submit-manuscript-dialog.title')"
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
                    :title="$t('submit-manuscript-dialog.step1.title')"
                    icon="mdi-check"
                    :done="step > 1"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        {{ $t('submit-manuscript-dialog.step1.subtitle') }}
                    </div>
                    <p class="q-ma-md">
                        {{
                            $t(
                                'submit-manuscript-dialog.step1.confirmation-text'
                            )
                        }}
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
                    :title="$t('submit-manuscript-dialog.step2.title')"
                    icon="mdi-account-search"
                    :error="validationError"
                    style="min-height: 275px"
                >
                    <div
                        class="q-mx-md text-body1 text-primary text-weight-medium"
                    >
                        {{ $t('submit-manuscript-dialog.step2.subtitle') }}
                    </div>
                    <p class="q-ma-md">
                        {{ $t('submit-manuscript-dialog.step2.p1') }}
                    </p>
                    <p class="q-ma-md">
                        {{ $t('submit-manuscript-dialog.step2.p2') }}
                    </p>
                    <UserSelect
                        v-model="divisionManagerUserId"
                        label="$t('common.division-manager')"
                        class="q-ma-md"
                        :disabled-emails="authorEmails"
                        :disabled-ids="[ownerId]"
                        :rules="[(val: number|null) => val !== null || $t('common.required')]"
                    />
                </q-step>
                <template #navigation>
                    <q-stepper-navigation class="flex justify-end">
                        <q-btn
                            color="primary"
                            :label="
                                step === 2
                                    ? $t('common.submit')
                                    : $t('common.next')
                            "
                            :disable="!agreeToTerms"
                            :loading="loading"
                            @click="step === 2 ? submit() : stepper?.next()"
                        />
                        <q-btn
                            v-close-popup
                            flat
                            :label="$t('common.cancel')"
                        />
                    </q-stepper-navigation>
                </template>
            </q-stepper>
        </q-form>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import { ManuscriptAuthorService } from '@/models/ManuscriptAuthor/ManuscriptAuthor';
import UserSelect from '@/models/User/components/UserSelect.vue';
import { QDialog, QForm, QStepper } from 'quasar';
import { Ref } from 'vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';
const { t } = useI18n();

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
        label: t('common.yes'),
        value: true,
    },
    {
        label: t('common.no'),
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
    return manuscriptAuthors.data.map(
        (author) => author.data.author?.data.email ?? ''
    );
}

async function getManuscriptOwnerId(): Promise<number> {
    const manuscriptRecord = await ManuscriptRecordService.find(
        props.manuscriptRecordId
    );
    return manuscriptRecord.data.user_id;
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
