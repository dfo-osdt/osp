<template>
    <q-timeline v-if="manuscriptRecord" color="primary" class="q-pa-lg">
        <q-timeline-entry heading
            ><div class="text-h4 text-primary">Manuscript Progress</div>
            <div
                class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
            >
                <ManuscriptStatusSpan :status="manuscriptRecord.data.status" />
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
            title="Submit for Management Review"
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
            :icon="managementReviewIcon"
            title="Completion of Management Review"
            :subtitle="managementReviewSubtitle"
            :color="managementReviewColor"
        >
            <p v-if="manuscriptRecord.data.status === 'withheld'">
                The manuscript does not comply with Fisheries and Oceans Canada
                National Policy for Science Publications and is withheld by DFO
                management.
            </p>
            <p v-else>
                The manuscript complies with Fisheries and Oceans Canada
                National Policy for Science Publications and is approved by DFO
                management. At this stage, you may now submit the manuscript to
                your target journal.
            </p>
        </q-timeline-entry>
        <q-timeline-entry
            v-if="manuscriptRecord.data.status !== 'withheld'"
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            title="Initial Submission to Target Publication"
            :subtitle="submittedToJournalSubtitle"
            :color="submittedToJournalColor"
        >
            <p>
                The manuscript was submitted to the target journal for
                peer-review. We track this stage to gather statistics on the
                time it takes to get manuscript accepted and published.
            </p>
            <div>
                <q-btn
                    v-if="
                        manuscriptRecord.data.submitted_to_journal_on === null
                    "
                    color="primary"
                    label="Mark as Submitted"
                    @click="showSubmittedToJournalDialog = true"
                />
            </div>
        </q-timeline-entry>
        <q-timeline-entry
            v-if="manuscriptRecord.data.status !== 'withheld'"
            class="q-mx-lg"
            icon="mdi-check-all"
            title="Accepted for Publication"
            :subtitle="acceptedToJournalSubtitle"
            :color="acceptedToJournalColor"
        >
            <p>
                The manuscript was accepted for publication by the target
                journal and is entering the production process. Confirming this
                step will create a new publication record where you can upload
                the final manuscript and update the publication details.
            </p>
            <div class="row q-gutter-md">
                <q-btn
                    v-if="manuscriptRecord.data.status !== 'accepted'"
                    color="primary"
                    label="Accepted for Publication"
                    @click="showAcceptedByJournalDialog = true"
                />
                <q-btn
                    v-if="
                        manuscriptRecord.data.status === 'reviewed' ||
                        manuscriptRecord.data.status === 'submitted'
                    "
                    color="negative"
                    outline
                    label="Withdraw Manuscript"
                />
                <q-btn
                    v-if="manuscriptRecord.data.status === 'accepted'"
                    color="primary"
                    label="Go to the Publication"
                    :to="`/publication/${manuscriptRecord.data.publication?.data.id}`"
                />
            </div>
        </q-timeline-entry>
        <SubmittedToJournalDialog
            v-model="showSubmittedToJournalDialog"
            :manuscript-record-id="manuscriptRecord.data.id"
            @updated="submittedToJournal"
        />
        <AcceptedByJournalDialog
            v-model="showAcceptedByJournalDialog"
            :manuscript-record="manuscriptRecord.data"
            @updated="acceptedToJournal"
        />
    </q-timeline>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';
import ManuscriptStatusSpan from '../components/ManuscriptStatusSpan.vue';
import SubmittedToJournalDialog from '../components/SubmittedToJournalDialog.vue';
import AcceptedByJournalDialog from '../components/AcceptedByJournalDialog.vue';
import { useQuasar } from 'quasar';

const $q = useQuasar();

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
    if (manuscriptRecord.value === null) return 'grey-7';
    if (manuscriptRecord.value.data.sent_for_review_at === null)
        return 'secondary';
    return 'primary';
});

const managementReviewSubtitle = computed(() => {
    if (manuscriptRecord.value === null) {
        return '';
    }
    if (manuscriptRecord.value.data.sent_for_review_at === null) {
        return 'Pending';
    }
    if (manuscriptRecord.value.data.reviewed_at === null) {
        return 'In Progress';
    }
    return (
        'Completed on ' +
        useLocaleDate(manuscriptRecord.value.data.reviewed_at).value
    );
});

const managementReviewColor = computed(() => {
    if (manuscriptRecord.value === null) return 'grey-7';
    if (manuscriptRecord.value.data.sent_for_review_at === null)
        return 'secondary';
    if (manuscriptRecord.value.data.status === 'withheld') return 'red';
    return 'primary';
});

const managementReviewIcon = computed(() => {
    if (manuscriptRecord.value?.data.status === 'withheld') {
        return 'mdi-alert-octagon';
    }
    return 'mdi-thumb-up-outline';
});

const submittedToJournalSubtitle = computed(() => {
    if (manuscriptRecord.value === null) {
        return '';
    }
    if (manuscriptRecord.value.data.submitted_to_journal_on === null) {
        return 'Pending';
    }
    return (
        'Submitted on ' +
        useLocaleDate(manuscriptRecord.value.data.submitted_to_journal_on).value
    );
});

const submittedToJournalColor = computed(() => {
    if (manuscriptRecord.value === null) return 'grey-7';
    if (manuscriptRecord.value.data.submitted_to_journal_on === null)
        return 'secondary';
    return 'primary';
});

const acceptedToJournalSubtitle = computed(() => {
    if (manuscriptRecord.value === null) {
        return '';
    }
    if (manuscriptRecord.value.data.accepted_on === null) {
        return 'Pending';
    }
    return (
        'Accepted on ' +
        useLocaleDate(manuscriptRecord.value.data.accepted_on).value
    );
});

const acceptedToJournalColor = computed(() => {
    if (manuscriptRecord.value === null) return 'grey-7';
    if (manuscriptRecord.value.data.accepted_on === null) return 'secondary';
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

// action dialogs and methods

// submitted to journal dialog
const showSubmittedToJournalDialog = ref(false);

function submittedToJournal(record: ManuscriptRecordResource) {
    manuscriptRecord.value = record;
    showSubmittedToJournalDialog.value = false;
    showUpdatedNotification();
}

// accept for publication
const showAcceptedByJournalDialog = ref(false);

function acceptedToJournal(record: ManuscriptRecordResource) {
    manuscriptRecord.value = record;
    showAcceptedByJournalDialog.value = false;
    showUpdatedNotification();
}

function showUpdatedNotification() {
    $q.notify({
        message: 'Manuscript status updated',
        color: 'positive',
        icon: 'mdi-check',
    });
}

onMounted(() => {
    getManuscriptRecord();
});
</script>

<style scoped></style>
