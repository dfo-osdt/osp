<template>
    <q-timeline color="primary" class="q-pa-lg">
        <q-timeline-entry heading
            ><div class="text-h4 text-primary">Management Review</div>
            <div
                class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
            >
                {{ processStatus }}
            </div>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            title="Submitted for Review"
            :color="submittedColor"
            :subtitle="sentForReview"
        />
        <template v-if="managementReviewSteps !== null">
            <ManagementReviewStepTimelineEntry
                v-for="(
                    managementReviewStep, index
                ) in managementReviewSteps.data"
                :key="managementReviewStep.data.id"
                v-model="managementReviewSteps.data[index]"
                @decision="decisionSubmitted"
            />
        </template>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-check-all"
            title="Management Review Complete"
            :color="completedColor"
            :subtitle="reviewCompletedOn"
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

const processStatus = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'Unknown';
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
            return 'Not Started - Submit your form to begin';
        case 'in_review':
            return 'In Progress';
        default:
            return 'Complete';
    }
    return null;
});

const sentForReview = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'Pending';
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
            return 'Pending';
        default:
            return (
                'Submitted on ' +
                useLocaleDate(manuscriptRecord.value.data.sent_for_review_at)
                    .value
            );
    }
});

const submittedColor = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'grey-7';
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
            return 'secondary';
        default:
            return 'primary';
    }
});

const reviewCompletedOn = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'Pending';
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
        case 'in_review':
            return 'Pending';
        default:
            return (
                'Submitted on ' +
                useLocaleDate(manuscriptRecord.value.data.reviewed_at).value
            );
    }
});

const completedColor = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'grey-7';
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
        case 'in_review':
            return 'secondary';
        default:
            return 'primary';
    }
});

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

const decisionSubmitted = async () => {
    await getManagementReviewSteps();
};
</script>

<style scoped></style>
