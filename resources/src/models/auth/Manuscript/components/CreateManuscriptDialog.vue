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
                        title="Manuscript Title"
                        icon="mdi-file-document-edit-outline"
                        :done="step > 2"
                    >
                        <div class="q-mb-md">
                            What is the working title of your manuscript?
                        </div>
                        <q-input
                            v-model="title"
                            outlined
                            label="Title"
                            placeholder="Enter the title of your manuscript"
                        />
                    </q-step>
                    <q-step
                        :name="3"
                        title="Create a new manuscript record"
                        icon="mdi-account-outline"
                        :done="step > 3"
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
                                @click="$refs.stepper.next()"
                            />
                            <q-btn
                                v-if="step > 1"
                                flat
                                color="primary"
                                label="Back"
                                @click="$refs.stepper.previous()"
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
// stepper
const step = ref(1);

// chosen type
const type = ref('primary');
const title = ref('');
</script>

<style scoped></style>
