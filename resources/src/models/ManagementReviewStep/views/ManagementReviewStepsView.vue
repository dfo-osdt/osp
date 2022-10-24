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
            title="Submission for Review"
            :color="submittedColor"
            :subtitle="sentForReview"
        >
            <p>
                Reviews the manuscript for compliance with the
                <a
                    href="https://www.dfo-mpo.gc.ca/copyright-droits-eng.htm"
                    target="_blank"
                    >Intellectual Property Policy/Copyright Act</a
                >, the
                <a
                    href="https://www.priv.gc.ca/en/privacy-topics/privacy-laws-in-canada/the-privacy-act/pa_brief/"
                    target="_blank"
                    >Privacy Act</a
                >, the Financial Administration Act (with respect to approvals
                of publication costs) and the
                <a
                    href="https://www.dfo-mpo.gc.ca/reports-rapports/vicr-virc/vicr-virc2012-eng.htm"
                    target="_blank"
                    >Values and Ethics Code for DFO</a
                >. It will also identify potential sensitive issues, solely for
                the purpose of briefing senior management and the Communications
                Branch prior to publication of the science paper. At no time,
                will the inclusion of sensitive material (e.g. data, scientific
                conclusions) prevent publication of scientific papers.
            </p>
            <p>
                Science management commits to a 10 working-day turnaround for
                sign-off of manuscripts for publication. If managers do not
                respond with an approval within 10 working days, authors may
                submit their manuscripts to the publisher.
            </p>
            <p>
                For more information please refer to the
                <a
                    href="https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html"
                >
                    Fisheries and Oceans Canada National Policy for Science
                    Publications </a
                >.
            </p>
        </q-timeline-entry>
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
            title="Completion of Management Review"
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
                'Completed on ' +
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
    managementReviewSteps.value = null;
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
    console.log('decision submitted');
    await getManagementReviewSteps();
    await getManuscriptRecord();
};
</script>

<style scoped></style>
