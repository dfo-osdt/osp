<template>
    <q-dialog persistent>
        <q-card style="width: 700px; max-width: 80vw" outlined>
            <q-card-section class="bg-teal-1 text-h6 text-accent">
                {{ $t('create-manuscript-record-dialog.title') }}
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
                        :title="$t('common.type-of-publication')"
                        icon="mdi-call-split"
                        :done="step > 1"
                        style="min-height: 275px"
                    >
                        <div>
                            {{
                                $t('create-manuscript-record-dialog.step1.text')
                            }}
                        </div>
                        <div class="q-pa-md">
                            <q-list>
                                <q-item v-ripple tag="label">
                                    <q-item-section avatar>
                                        <q-radio v-model="type" val="primary" />
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label class="text-body1">{{
                                            $t('common.primary-publication')
                                        }}</q-item-label>
                                        <q-item-label
                                            class="text-body2 text-grey-8"
                                            >{{
                                                $t(
                                                    'create-manuscript-record-dialog.primary-pub-desc'
                                                )
                                            }}
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
                                        <q-item-label class="text-body1">{{
                                            $t('common.secondary-publication')
                                        }}</q-item-label>
                                        <q-item-label
                                            class="text-body2 text-grey-8"
                                            >{{
                                                $t(
                                                    'create-manuscript-record-dialog.secondary-pub-desc'
                                                )
                                            }}</q-item-label
                                        >
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </div>
                    </q-step>
                    <q-step
                        :name="2"
                        :title="
                            $t('create-manuscript-record-dialog.step2.title')
                        "
                        icon="mdi-file-document-edit-outline"
                        :done="step > 2"
                        :error="!manuscriptDetailFormValid"
                        style="min-height: 275px"
                    >
                        <q-form ref="manuscriptDetailForm">
                            <div class="q-mb-md">
                                {{
                                    $t(
                                        'create-manuscript-record-dialog.what-is-the-working-title-of-your-manuscript'
                                    )
                                }}
                            </div>
                            <q-input
                                v-model="title"
                                outlined
                                :label="$t('common.title')"
                                :placeholder="
                                    $t(
                                        'create-manuscript-record-dialog.enter-the-title-of-your-manuscript'
                                    )
                                "
                                :rules="[
                                    (val) =>
                                        val.length > 0 || $t('common.required'),
                                ]"
                            />
                            <div class="q-my-md">
                                {{
                                    $t(
                                        'create-manuscript-record-dialog.select-region-text'
                                    )
                                }}
                            </div>
                            <region-select
                                v-model="regionId"
                                outlined
                                :placeholder="
                                    $t(
                                        'manuscript.please-select-the-lead-region'
                                    )
                                "
                                :rules="[
                                (val: number) =>
                                    val !== null || $t('common.required'),
                            ]"
                            />
                        </q-form>
                    </q-step>
                    <q-step
                        :name="3"
                        :title="$t('common.create')"
                        icon="mdi-file-document-check"
                        :done="step > 3"
                        style="min-height: 275px"
                    >
                        <div class="q-pa-md">
                            {{
                                $t(
                                    'create-manuscript-record-dialog.create-text'
                                )
                            }}
                        </div>
                    </q-step>
                    <template #navigation
                        ><q-stepper-navigation class="flex justify-end">
                            <q-btn
                                color="primary"
                                :label="
                                    step === 3
                                        ? $t('common.create')
                                        : $t('common.contiue')
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
    const record: BaseManuscriptRecord = {
        type: type.value,
        title: title.value,
        region_id: regionId.value ?? 1,
    };

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
