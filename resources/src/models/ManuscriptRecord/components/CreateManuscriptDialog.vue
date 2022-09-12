<template>
    <q-dialog persistent>
        <q-card style="width: 700px; max-width: 80vw" outlined>
            <q-card-section class="bg-teal-1 text-h6 text-accent">
                Create a new manuscript record
            </q-card-section>
            <q-separator />
            <q-card-section class="bg-grey-1 q-pa-none text-body1">
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
                        title="Type of publication"
                        icon="mdi-call-split"
                        :done="step > 1"
                        style="min-height: 275px"
                    >
                        <div>
                            Please select the type of manuscript record you want
                            to create. Do you intend to submit this manuscript
                            to a primary or secondary journal?
                        </div>
                        <div class="q-pa-md">
                            <q-list>
                                <q-item v-ripple tag="label">
                                    <q-item-section avatar>
                                        <q-radio v-model="type" val="primary" />
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label class="text-body1"
                                            >Primary Publication</q-item-label
                                        >
                                        <q-item-label
                                            class="text-body2 text-grey-8"
                                            >A publication in a primary
                                            peer-reviewed publications
                                            (scientific journals, special
                                            issues, book chapters, books and
                                            conference proceedings.)
                                        </q-item-label>
                                    </q-item-section>
                                </q-item>

                                <q-item v-ripple tag="label">
                                    <q-item-section avatar top>
                                        <q-radio
                                            v-model="type"
                                            val="secondary"
                                        />
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label class="text-body1"
                                            >Secondary Publication</q-item-label
                                        >
                                        <q-item-label
                                            class="text-body2 text-grey-8"
                                            >A publication in a Fisheries and
                                            Oceans Canada’s (DFO’s) secondary
                                            science publications such as the DFO
                                            series “Canadian Technical Report of
                                            Fisheries and Aquatic Sciences,”
                                            “Canadian Data Report of Fisheries
                                            and Aquatic Sciences,” and other
                                            series.</q-item-label
                                        >
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </div>
                    </q-step>
                    <q-step
                        :name="2"
                        title="Manuscript Details"
                        icon="mdi-file-document-edit-outline"
                        :done="step > 2"
                        :error="!manuscriptDetailFormValid"
                        style="min-height: 275px"
                    >
                        <q-form ref="manuscriptDetailForm">
                            <div class="q-mb-md">
                                What is the working title of your manuscript?
                            </div>
                            <q-input
                                v-model="title"
                                outlined
                                label="Title"
                                placeholder="Enter the title of your manuscript"
                                :rules="[
                                    (val) =>
                                        val.length > 0 || 'Title is required',
                                ]"
                            />
                            <div class="q-my-md">
                                Please select the lead DFO region responsible
                                for the review of this manuscript.
                            </div>
                            <region-select
                                v-model="regionId"
                                outlined
                                placeholder="Please select the lead region"
                                :rules="[
                                (val: number) =>
                                    val !== null || 'Lead region is required',
                            ]"
                            />
                        </q-form>
                    </q-step>
                    <q-step
                        :name="3"
                        title="Create"
                        icon="mdi-file-document-check"
                        :done="step > 3"
                        style="min-height: 275px"
                    >
                        <div class="q-pa-md">
                            Once created, you will be taken to the manuscript
                            record form. The manuscript record form will be
                            marked as a draft and only be visible to you an the
                            people you invite as observers. Once the form is
                            complete you will be able to submit it for review.
                        </div>
                    </q-step>
                    <template #navigation
                        ><q-stepper-navigation>
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
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import RegionSelect from '@/models/Region/components/RegionSelect.vue';

import { QStepper, QForm } from 'quasar';
import { Ref } from 'vue';
import {
    BaseManuscriptRecord,
    ManuscriptRecordService,
    ManuscriptRecordType,
} from '../ManuscriptRecord';

// router
const router = useRouter();

// stepper
const stepper: Ref<QStepper | null> = ref(null);
const step = ref(1);
const manuscriptDetailForm: Ref<QForm | null> = ref(null);
const manuscriptDetailFormValid = ref(true);

async function next() {
    if (step.value === 3) {
        // create manuscript
        create();
        return;
    }
    if (step.value === 2) {
        const valid = await manuscriptDetailForm.value?.validate();
        manuscriptDetailFormValid.value = valid === undefined ? true : valid;
        if (!manuscriptDetailFormValid.value) return;
    }
    stepper.value?.next();
}

// information required to create a manuscript record
const type: Ref<ManuscriptRecordType> = ref('primary');
const title = ref('');
const regionId: Ref<number | null> = ref(null);

async function create() {
    console.log('creating the record');

    const record: BaseManuscriptRecord = {
        type: type.value,
        title: title.value,
        region_id: regionId.value ?? 1,
    };

    console.log(record);

    // create the manuscript record
    await ManuscriptRecordService.create(record)
        .then((response) => {
            console.log(response);
            router.push({
                name: 'manuscript',
                params: { id: response.data.id },
            });
        })
        .catch((error) => {
            console.log(error);
        });
}
</script>

<style scoped></style>
