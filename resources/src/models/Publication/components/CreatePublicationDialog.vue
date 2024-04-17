<template>
    <BaseDialog :title="$t('create-publication-dialog.title')" persistent>
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
                :title="$t('create-publication-dialog.before-your-start')"
                icon="mdi-call-split"
                active-icon="mdi-information-variant"
                :done="step > 1"
                style="min-height: 275px"
            >
                <div class="text-body1 text-primary text-weight-medium q-mb-md">
                    {{ $t('create-publication-dialog.before-continuing') }}
                </div>
                <p>
                    {{ $t('create-publication-dialog.step1.p1') }}
                </p>
                <q-banner rounded class="bg-teal-1">
                    <template #avatar>
                        <q-icon
                            name="mdi-lifebuoy"
                            size="2rem"
                            color="primary"
                        />
                    </template>
                    {{ $t('create-publication-dialog.step1.warning') }}
                </q-banner>
            </q-step>
            <q-step
                :name="2"
                :title="$t('create-publication-dialog.publication-details')"
                icon="mdi-file-document-edit-outline"
                :error="!detailsValid"
                :done="step > 2"
                style="min-height: 275px"
            >
                <q-form ref="detailsForm" class="q-ma-md">
                    <div class="text-body1 text-primary text-weight-medium">
                        {{
                            $t('create-publication-dialog.publication-details')
                        }}
                    </div>
                    <q-separator class="q-mb-md" />
                    <q-input
                        v-model="title"
                        :label="$t('common.title')"
                        class="q-mb-md"
                        outlined
                        :rules="[
                            (val) => val.length > 0 || $t('common.required'),
                        ]"
                    />
                    <JournalSelect
                        v-model="journalId"
                        class="q-mb-md"
                        :rules="[
                            (val: number) =>
                                val !== null || $t('common.required'),
                        ]"
                    ></JournalSelect>
                    <DoiInput v-model="doi" class="q-mb-md" />
                </q-form>
            </q-step>
            <q-step
                :name="3"
                :title="$t('create-publication-dialog.dates-and-access')"
                icon="mdi-calendar"
                :error="!datesValid"
                :done="step > 3"
                style="min-height: 275px"
            >
                <q-form ref="datesForm">
                    <div class="text-body1 text-primary text-weight-medium">
                        {{ $t('create-publication-dialog.publication-dates') }}
                    </div>
                    <q-separator class="q-mb-md" />
                    <DateInput
                        v-model="acceptedOn"
                        :label="
                            $t('create-publication-dialog.accepted-on-optional')
                        "
                        class="q-mb-md"
                    />
                    <DateInput
                        v-model="publishedOn"
                        :label="$t('create-publication-dialog.published-on')"
                        class="q-mb-md"
                        required
                    />
                    <div
                        class="text-body1 q-mt-lg text-primary text-weight-medium"
                    >
                        {{ $t('create-publication-dialog.publication-access') }}
                    </div>
                    <q-separator class="q-mb-md" />
                    <q-toggle
                        v-model="isOpenAccess"
                        :label="$t('common.published-as-open-access')"
                    />
                    <DateInput
                        v-if="!isOpenAccess"
                        v-model="embargoedUntil"
                        :label="$t('common.embargoed-until')"
                        :required="!isOpenAccess"
                    />
                </q-form>
            </q-step>
            <template #navigation
                ><q-stepper-navigation class="flex justify-end">
                    <q-btn
                        color="primary"
                        :label="
                            step === 3 ? $t('common.create') : $t('common.next')
                        "
                        class="q-mr-sm"
                        @click="next()"
                    />
                    <q-btn
                        v-if="step > 1"
                        flat
                        color="primary"
                        :label="$t('common.back')"
                        @click="stepper?.previous()"
                    />
                    <q-btn
                        v-close-popup
                        flat
                        :label="$t('common.cancel')"
                    /> </q-stepper-navigation
            ></template>
        </q-stepper>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import DateInput from '@/components/DateInput.vue';
import JournalSelect from '@/models/Journal/components/JournalSelect.vue';
import { QForm, QStepper } from 'quasar';
import { Ref } from 'vue';
import {
    PublicationCreate,
    PublicationResource,
    PublicationService,
} from '../Publication';
import DoiInput from './DoiInput.vue';

const today = new Date().toISOString().split('T')[0].replace(/-/g, '/');

const journalId = ref<number | null>(null);
const title = ref('');
const doi = ref('');
const acceptedOn = ref('');
const publishedOn = ref(today);
const embargoedUntil = ref('');
const isOpenAccess = ref(false);

//form validation
const detailsForm: Ref<QForm | null> = ref(null);
const datesForm: Ref<QForm | null> = ref(null);
const detailsValid = ref(true);
const datesValid = ref(true);
/*
 * Watch for open access to change - if it does, clear the embargoed
 * until date and reset the form validation
 */
watch(
    isOpenAccess,
    (val) => {
        if (val) {
            embargoedUntil.value = '';
            datesForm.value?.resetValidation();
            datesValid.value = true;
        }
    },
    { immediate: true },
);

// stepper
const stepper: Ref<QStepper | null> = ref(null);
const step = ref(1);

async function next() {
    if (step.value === 2) {
        const valid = await detailsForm.value?.validate();
        if (!valid) {
            detailsValid.value = false;
            return;
        } else {
            detailsValid.value = true;
            stepper.value?.next();
            return;
        }
    }
    if (step.value === 3) {
        const valid = await datesForm.value?.validate();
        if (!valid) {
            datesValid.value = false;
            return;
        } else {
            create();
            return;
        }
    }
    stepper.value?.next();
}

// define the created emit
const emit = defineEmits<{
    (e: 'created', publication: PublicationResource): void;
}>();

/** Create the publication */
async function create() {
    const publication: PublicationCreate = {
        status: 'published',
        title: title.value,
        doi: doi.value,
        is_open_access: isOpenAccess.value,
        accepted_on: acceptedOn.value,
        published_on: publishedOn.value,
        embargoed_until: embargoedUntil.value,
        journal_id: journalId.value ?? 0,
    };

    try {
        const pub = await PublicationService.create(publication);
        emit('created', pub);
    } catch (e) {
        console.error(e);
    }
}
</script>

<style scoped></style>
