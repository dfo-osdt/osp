<template>
    <q-timeline v-if="manuscriptRecord" color="primary" class="q-pa-lg">
        <q-timeline-entry heading
            ><div class="text-h4 text-primary">Manuscript Progress</div>
            <div
                class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
            >
                IN REVIEW
            </div>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-plus"
            title="Manuscript Record Created"
            :subtitle="createdSubtitle"
            :color="createdColor"
        >
            <p>
                At this stage, only the applicant and authors can see and edit
                the manuscript record.
            </p>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            title="Submitted for Management Review"
            :subtitle="submittedReviewSubtitle"
            :color="submittedReviewColor"
        >
            <p>
                The manuscript is initially submitted to a division manager or
                director (NCR) for management review.
            </p>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-thumb-up-outline"
            title="Management Review Complete"
            subtitle="Pending"
            color="secondary"
        >
            <p>
                The manuscript complies with Fisheries and Oceans Canada
                National Policy for Science Publications and is approved by DFO
                management. At this stage, you may now submit the manuscript to
                your target journal.
            </p>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            title="Initial Submission to Target Publication"
            subtitle="Pending"
            color="secondary"
        >
            <p>
                The manuscript was submitted to the target journal for
                peer-review. We track this stage to gather statistics on the
                time it takes to get a manuscript published.
            </p>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-check-all"
            title="Accepted for Publication"
            subtitle="Pending"
            color="secondary"
        >
            <p>
                The manuscript was accepted for publication by the target
                journal. Confirming this step will create a new publication
                where you can upload the final manuscript and update the
                publication details.
            </p>
        </q-timeline-entry>
    </q-timeline>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';

const props = defineProps<{
    id: number;
}>();
const manuscriptRecord: Ref<ManuscriptRecordResource | null> = ref(null);

const createdSubtitle = computed(() => {
    if (manuscriptRecord.value === null) {
        return '';
    }
    return (
        'Created on ' +
        useLocaleDate(manuscriptRecord.value.data.created_at).value
    );
});

const createdColor = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'grey-7';
    }
    return 'primary';
});

const submittedReviewSubtitle = computed(() => {
    if (manuscriptRecord.value === null) {
        return '';
    }
    if (manuscriptRecord.value.data.sent_for_review_at === null) {
        return 'Pending';
    }
    return (
        'Submitted on ' +
        useLocaleDate(manuscriptRecord.value.data.sent_for_review_at).value
    );
});

const submittedReviewColor = computed(() => {
    if (manuscriptRecord.value === null) {
        return 'grey-7';
    }
    return 'primary';
});

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
});
</script>

<style scoped></style>
