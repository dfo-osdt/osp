<template>
    <BaseDialog title="Create a Publication" persistent>
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
                title="Before your start"
                icon="mdi-call-split"
                active-icon="mdi-information-variant"
                :done="step > 1"
                style="min-height: 275px"
            >
                <div class="text-body1 text-accent text-weight-medium q-mb-md">
                    Before Continuing...
                </div>
                <p>
                    This wizard lets you add published publications to the
                    portal; allowing you to share past publications or to add
                    publications where the manuscript record form (MRF) was done
                    through other means.
                </p>
                <q-banner rounded class="bg-teal-1">
                    <template #avatar>
                        <q-icon
                            name="mdi-lifebuoy"
                            size="2rem"
                            color="primary"
                        />
                    </template>
                    If you are here to get your manuscript reviewed prior to
                    submission to a journal, in order to comply with the
                    national policy for science publication, do not continue,
                    create a new manuscript record instead.
                </q-banner>
            </q-step>
            <q-step
                :name="2"
                title="Publication Details"
                icon="mdi-file-document-edit-outline"
                :error="!detailsValid"
                :done="step > 2"
                style="min-height: 275px"
            >
                <q-form ref="detailsForm" class="q-ma-md">
                    <div class="text-body1">
                        Publication Details<RequiredSpan />
                    </div>
                    <q-separator class="q-mb-md" />
                    <q-input
                        v-model="title"
                        label="Title"
                        class="q-mb-md"
                        outlined
                        :rules="[
                            (val) => val.length > 0 || 'Title is required',
                        ]"
                    />
                    <JournalSelect
                        v-model="journalId"
                        class="q-mb-md"
                        :rules="[
                    (val: number) =>
                        val !== null || 'Journal is required',
                ]"
                    ></JournalSelect>
                    <DoiInput v-model="doi" class="q-mb-md" />
                </q-form>
            </q-step>
            <q-step
                :name="3"
                title="Dates and Access"
                icon="mdi-calendar"
                :done="step > 3"
                style="min-height: 275px"
            >
                <q-form ref="datesForm">
                    <div class="text-body1">
                        Publication Dates<RequiredSpan />
                    </div>
                    <q-separator class="q-mb-md" />
                    <DateInput
                        v-model="acceptedOn"
                        label="Accepted On"
                        class="q-mb-md"
                        required
                    />
                    <DateInput
                        v-model="publishedOn"
                        label="Published On"
                        class="q-mb-md"
                    />
                    <div class="text-body1 q-mt-lg">Publication Access</div>
                    <q-separator class="q-mb-md" />
                    <q-toggle
                        v-model="isOpenAccess"
                        label="Published as Open Access"
                    />
                    <DateInput
                        v-if="!isOpenAccess"
                        v-model="embargoedUntil"
                        label="Embargoed Until"
                        :required="!isOpenAccess"
                    />
                </q-form>
            </q-step>
            <template #navigation
                ><q-stepper-navigation class="flex justify-end">
                    <q-btn
                        color="primary"
                        :label="step === 3 ? 'Create' : 'Continue'"
                        class="q-mr-sm"
                        @click="next()"
                    />
                    <q-btn
                        v-if="step > 1"
                        flat
                        color="primary"
                        label="Back"
                        @click="stepper?.previous()"
                    />
                    <q-btn
                        v-close-popup
                        flat
                        label="Cancel"
                    /> </q-stepper-navigation
            ></template>
        </q-stepper>
    </BaseDialog>
</template>

<script setup lang="ts">
import BaseDialog from '@/components/BaseDialog.vue';
import DateInput from '@/components/DateInput.vue';
import RequiredSpan from '@/components/RequiredSpan.vue';
import JournalSelect from '@/models/Journal/components/JournalSelect.vue';
import { QForm, QStepper } from 'quasar';
import { Ref } from 'vue';
import DoiInput from './DoiInput.vue';

const today = new Date().toISOString().split('T')[0].replace(/-/g, '/');

const journalId = ref<number | null>(null);
const title = ref('');
const doi = ref('');
const acceptedOn = ref(today);
const publishedOn = ref(today);
const embargoedUntil = ref('');
const isOpenAccess = ref(false);

//form validation
const detailsForm: Ref<QForm | null> = ref(null);
const datesForm: Ref<QForm | null> = ref(null);
const detailsValid = ref(true);
const datesValid = ref(true);

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
            stepper.value?.next();
        }
    }
    if (step.value === 3) {
        const valid = await datesForm.value?.validate();
        if (!valid) {
            datesValid.value = false;
            return;
        } else {
            stepper.value?.next();
        }
    }
    stepper.value?.next();
}
</script>

<style scoped></style>
