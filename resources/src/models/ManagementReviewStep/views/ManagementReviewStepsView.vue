<template>
    <q-timeline color="primary" class="q-pa-lg">
        <q-timeline-entry heading
            ><div class="text-h4 text-primary">Management Review</div>
            <div
                class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
            >
                In Progress
            </div>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            title="Submitted for Review"
            subtitle="Sent on 2021-05-05"
        />
        <template v-if="managementReviewSteps !== null">
            <ManagementReviewStepTimelineEntry
                v-for="(
                    managementReviewStep, index
                ) in managementReviewSteps.data"
                :key="managementReviewStep.data.id"
                v-model="managementReviewSteps.data[index]"
            />
        </template>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-check-all"
            title="Management Review Complete"
            subtitle="Pending"
        />
    </q-timeline>
</template>

<script setup lang="ts">
import {
    ManagementReviewStepResourceList,
    ManagementReviewStepService,
} from '../ManagementReviewStep';
import ManagementReviewStepTimelineEntry from '../components/ManagementReviewStepTimelineEntry.vue';
import { Ref } from 'vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '@/models/ManuscriptRecord/ManuscriptRecord';

const props = defineProps<{
    id: number;
}>();

const managementReviewSteps: Ref<ManagementReviewStepResourceList | null> =
    ref(null);
const manuscriptRecord: Ref<ManuscriptRecordResource | null> = ref(null);

async function getManagementReviewSteps() {
    await ManagementReviewStepService.list(props.id)
        .then((response) => {
            managementReviewSteps.value = response;
        })
        .catch((error) => {
            console.log(error);
        });
}

async function getManuscriptRecord() {
    await ManuscriptRecordService.find(props.id)
        .then((response) => {
            manuscriptRecord.value = response;
        })
        .catch((error) => {
            console.log(error);
        });
}

onMounted(() => {
    getManuscriptRecord();
    getManagementReviewSteps();
});
</script>

<style scoped></style>
