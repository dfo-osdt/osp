<template>
    <q-timeline color="primary" class="q-px-md">
        <q-timeline-entry heading
            ><div class="text-h4 text-primary">
                {{ $t('management-review-step-view.title') }}
            </div>
            <div
                class="text-subtitle2 text-weight-bold text-grey-7 text-uppercase"
            >
                {{ processStatus }}
            </div>
        </q-timeline-entry>
        <q-timeline-entry
            class="q-mx-lg"
            icon="mdi-send-check-outline"
            :title="$t('management-review-step-view.submission-for-review')"
            :color="submittedColor"
            :subtitle="sentForReview"
        >
            <i18n-t keypath="policy.message.p1" tag="p" scope="global">
                <template #copyrightsAct>
                    <a
                        :href="
                            $t(
                                'policy.intellectual-property-policy-copyright-act-link',
                            )
                        "
                        target="_blank"
                        >{{
                            $t(
                                'policy.intellectual-property-policy-copyright-act',
                            )
                        }}</a
                    >
                </template>
                <template #privacyAct>
                    <a :href="$t('policy.privacy-act-link')" target="_blank">{{
                        $t('policy.privacy-act')
                    }}</a>
                </template>
                <template #valueAndEthicsCode>
                    <a
                        :href="$t('policy.values-and-ethics-code-for-dfo-link')"
                        target="_blank"
                        >{{ $t('policy.values-and-ethics-code-for-dfo') }}</a
                    >
                </template>
            </i18n-t>
            <p>
                {{ $t('policy.message.p2') }}
            </p>
            <p>
                {{ $t('policy.p2') }}
            </p>
            <i18n-t keypath="policy.for-more-info" tag="p" scope="global">
                <template #policy>
                    <a
                        :href="
                            $t(
                                'policy.national-policy-for-science-publications-link',
                            )
                        "
                        target="_blank"
                        >{{
                            $t(
                                'policy.national-policy-for-science-publications',
                            )
                        }}</a
                    >
                </template>
            </i18n-t>
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
            :title="
                $t(
                    'management-review-step-view.completion-of-management-review',
                )
            "
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
const { t } = useI18n();

const props = defineProps<{
    id: number;
}>();

const emit = defineEmits<{
    (e: 'update-manuscript', manuscript: ManuscriptRecordResource): void;
}>();

const managementReviewSteps: Ref<ManagementReviewStepResourceList | null> =
    ref(null);
const manuscriptRecord: Ref<ManuscriptRecordResource | null> = ref(null);

const processStatus = computed(() => {
    if (manuscriptRecord.value === null) {
        return t('common.unknown');
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
            return t(
                'management-review-step-view.not-started-submit-your-form-to-begin',
            );
        case 'in_review':
            return t('common.in-progress');
        default:
            return t('common.complete');
    }
    return null;
});

const sentForReview = computed(() => {
    if (manuscriptRecord.value === null) {
        return t('common.pending');
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
            return t('common.pending');
        default:
            return (
                t('common.submitted-on') +
                ' ' +
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
            return 'yellow-8';
        default:
            return 'primary';
    }
});

const reviewCompletedOn = computed(() => {
    if (manuscriptRecord.value === null) {
        return t('common.pending');
    }
    switch (manuscriptRecord.value.data.status) {
        case 'draft':
        case 'in_review':
            return t('common.pending');
        default:
            return (
                t('common.completed-on') +
                ' ' +
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
            return 'yellow-8';
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
            emit('update-manuscript', manuscriptRecord.value);
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
    await getManuscriptRecord();
};
</script>

<style scoped></style>
