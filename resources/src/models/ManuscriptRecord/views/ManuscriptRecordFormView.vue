<template>
    <q-banner
        v-if="showPublishBanner"
        class="bg-secondary text-white q-px-md q-pt-lg q-pb-md"
    >
        <div class="flex justify-between items-center">
            <div class="text-subtitle1 flex items-center">
                <q-icon
                    name="mdi-information-outline"
                    size="md"
                    class="q-mr-sm"
                />
                <span>
                    This manuscript has been reviewed but still needs to be
                    marked as published by the applicant.
                </span>
            </div>
            <div>
                <q-btn
                    flat
                    class="text-white"
                    icon-right="mdi-arrow-right-thin-circle-outline"
                    :to="`/manuscript/${id}/progress`"
                />
            </div>
        </div>
    </q-banner>
    <div class="q-pa-lg">
        <div class="q-mt-md q-mb-lg row justify-between">
            <div class="col-8">
                <div class="text-h4 text-primary">Manuscript Record Form</div>
                <div
                    v-if="manuscriptResource"
                    class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs ellipsis"
                >
                    {{ manuscriptResource.data.title }}
                </div>
            </div>
            <div v-if="manuscriptResource" class="col-grow">
                <q-card bordered flat class="q-pa-md">
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            Manuscript Type
                        </div>
                        <ManuscriptTypeBadge
                            :type="manuscriptResource.data.type"
                            outline
                            class="text-body2"
                        />
                    </div>
                    <q-separator class="q-my-sm" />
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            Status
                        </div>
                        <ManuscriptStatusBadge
                            :status="manuscriptResource.data.status"
                            outline
                            class="text-body2"
                        />
                    </div>
                    <q-separator class="q-my-sm" />
                    <div class="row justify-between">
                        <div
                            class="text-caption text-uppercase text-weight-bold text-grey-7 q-py-xs"
                        >
                            Applicant
                        </div>
                        <div class="text-body2 text-grey-7 q-py-xs">
                            {{
                                manuscriptResource.data.user?.data.first_name +
                                ' ' +
                                manuscriptResource.data.user?.data.last_name
                            }}
                            ({{ manuscriptResource.data.user?.data.email }})
                        </div>
                    </div>
                </q-card>
            </div>
        </div>
        <ManageManuscriptAuthorsCard
            ref="manuscriptAuthorsCard"
            :manuscript-record-id="id"
            :readonly="isManuscriptReadOnly"
            class="q-mb-lg"
            secondary
        />
        <ContentCard class="q-mb-lg" secondary>
            <template #title>General Information</template>
            <template #title-right>
                <FormSectionStatusIcon :status="generalSectionStatus" />
            </template>
            <div class="flex justify-end">
                <RequiredSpan
                    :is-definition="true"
                    class="text-caption text-grey-8"
                />
            </div>
            <template v-if="manuscriptResource?.data">
                <q-form ref="generalInformationForm">
                    <div class="q-mb-md">
                        <div class="q-ml-xs">
                            <div
                                class="text-body1 text-primary text-weight-medium"
                            >
                                Manuscript's Working Title<RequiredSpan />
                            </div>
                            <div class="q-my-xs">
                                <p>
                                    Enter to the manuscript's working title. You
                                    will be able to modify the title when you
                                    update this manuscript status to published.
                                </p>
                            </div>
                        </div>
                        <q-input
                            id="workingTitle"
                            v-model="manuscriptResource.data.title"
                            outlined
                            bg-color="white"
                            label="Manuscript's Working Title"
                            :disable="loading"
                            :readonly="isManuscriptReadOnly"
                            :rules="[
                                (val) => val.length > 0 || 'Title is required',
                            ]"
                        ></q-input>
                    </div>

                    <div class="q-mb-lg">
                        <div class="q-ml-xs">
                            <div
                                class="text-body1 text-primary text-weight-medium"
                            >
                                Lead Region<RequiredSpan />
                            </div>
                            <div class="q-my-xs">
                                <p>
                                    Select the DFO region responsible for the
                                    review of this manuscript. If more than one
                                    region is responsible, select the region
                                    that will lead the review.
                                </p>
                            </div>
                        </div>
                        <RegionSelect
                            v-model="manuscriptResource.data.region_id"
                            label="DFO Lead Region"
                            outlined
                            :disable="loading"
                            :readonly="isManuscriptReadOnly"
                            bg-color="white"
                        />
                    </div>

                    <QuestionEditor
                        v-model="manuscriptResource.data.abstract"
                        title="Abstract"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        required
                        class="q-mb-lg"
                    >
                        <p>Copy your manuscript's abstract here.</p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.pls"
                        title="Plain Language Summary"
                        :disable="loading || PLSLoading"
                        :readonly="isManuscriptReadOnly"
                        required
                        class="q-mb-md"
                    >
                        <p>
                            Plain Language Summary (PLS) of the manuscript to
                            explain the relevance of the science to the
                            Department and to promote the value of science
                            through outreach and communication. It is intended
                            for internal use to identify linkages to programs,
                            policies and stakeholders and for use in the plain
                            language promotion of science. Tips for writing a
                            PLS can be found
                            <a
                                href="https://www.agu.org/-/media/Files/Share-and-Advocate-for-Science/Toolkit---PLS.pdf"
                                target="_blank"
                                >here</a
                            >.
                        </p>
                        <div class="row justify-end q-mr-sm">
                            <div
                                class="text-body1 text-primary q-pt-sm q-pr-md"
                            >
                                Need some help? Using your manuscript's
                                abstract, we can generate a draft PLS.
                            </div>
                            <div>
                                <q-btn
                                    class="q-mb-md"
                                    color="primary"
                                    label="Generate PLS"
                                    icon="mdi-brain"
                                    outline
                                    rounded
                                    :disable="!enablePLSPrompt"
                                    :loading="PLSLoading"
                                    @click="generatePLS"
                                >
                                </q-btn>
                                <q-tooltip
                                    v-if="!enablePLSPrompt"
                                    class="text-body2"
                                >
                                    {{ plsDisabledTooltip }}
                                </q-tooltip>
                            </div>
                        </div>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="
                            manuscriptResource.data.scientific_implications
                        "
                        title="Scientific Implications"
                        :disable="loading"
                        required
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            Describe the scientific implications of the paper.
                            (i.e. field, importance, focus, purpose, benefits,
                            etc.)
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.relevant_to"
                        title="Relevant to programs, projects, etc."
                        :disable="loading"
                        required
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            Describe how the paper is relevant to programs,
                            projects, acts, initiatives, etc. (i.e. how it
                            supports the department's mandate, how it supports
                            the department's strategic plan, how it supports
                            regional priorities, etc.)
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.regions_and_species"
                        title="Geographical Scope and Species"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            Describe the geographical scope/region and (if
                            applicable) species (to include common names) of the
                            paper.
                        </p>
                    </QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.additional_information"
                        title="Additional Information of Importance"
                        :disable="loading"
                        :readonly="isManuscriptReadOnly"
                        class="q-mb-md"
                    >
                        <p>
                            Provide any additional information of importance to
                            the manuscript.
                        </p>
                    </QuestionEditor>
                </q-form>
            </template>
            <template v-else>
                <q-skeleton type="text" />
            </template>
        </ContentCard>
        <ManageFundingSourcesCard
            v-if="manuscriptResource?.data"
            :fundable-id="manuscriptResource.data.id"
            :readonly="isManuscriptReadOnly"
            fundable-type="manuscript-records"
        />
        <ContentCard class="q-mb-md" secondary>
            <template #title>Attach Manuscript</template>
            <template #title-right
                ><FormSectionStatusIcon
                    :status="
                        manuscriptResource?.data.manuscript_pdf
                            ? 'complete'
                            : 'incomplete'
                    "
            /></template>
            <p>
                Upload the most recent copy of your manuscript as a PDF. This
                file can be updated as required by the applicant, even after
                submitting the manuscript.
            </p>
            <template v-if="manuscriptResource?.data.manuscript_pdf">
                <q-card outlined class="q-mb-md">
                    <q-list>
                        <q-item>
                            <q-item-section>
                                <q-item-label>{{
                                    manuscriptResource.data.manuscript_pdf
                                        .file_name
                                }}</q-item-label>
                                <q-item-label caption>
                                    {{
                                        manuscriptResource.data.manuscript_pdf
                                            .size_bytes / 1000
                                    }}
                                    KB uploaded on
                                    {{
                                        new Date(
                                            manuscriptResource.data.manuscript_pdf.created_at
                                        ).toLocaleString()
                                    }}
                                </q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-btn
                                    icon="mdi-file-download-outline"
                                    color="primary"
                                    :loading="loading"
                                    :href="`api/manuscript-records/${id}/pdf`"
                                />
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </template>
            <q-file
                v-if="manuscriptResource?.data.can_attach_manuscript"
                v-model="manuscriptFile"
                outlined
                use-chips
                :label="
                    manuscriptResource?.data.manuscript_pdf
                        ? 'Upload a new version of the manuscript'
                        : 'Upload manuscript'
                "
                hint="Only PDF files are accepted. Maximum file size is 10MB."
                accept="application/pdf"
                max-file-size="10000000"
                counter
                :loading="uploadingFile"
                @rejected="onFileRejected"
            >
                <template #prepend>
                    <q-icon name="mdi-file-pdf-box" />
                </template>
                <template #append>
                    <q-btn
                        color="primary"
                        :loading="uploadingFile"
                        :disable="!manuscriptFile"
                        label="Upload"
                        @click="upload"
                    />
                </template>
            </q-file>
        </ContentCard>
        <q-card-actions
            v-if="manuscriptResource?.can?.update"
            ref="actionsSection"
            align="right"
            class="q-mb-lg"
        >
            <q-btn
                class="q-mt-md"
                color="primary"
                :loading="loading"
                label="Save"
                @click="save"
            />
            <div class="q-ml-sm">
                <q-tooltip>
                    {{
                        canSubmit
                            ? 'Submit for review'
                            : 'Please complete all sections and save to submit'
                    }}
                </q-tooltip>
                <q-btn
                    class="q-mt-md"
                    color="primary"
                    :loading="loading"
                    :disable="!canSubmit"
                    label="Submit"
                    @click="submit"
                ></q-btn>
            </div>
            <SubmitManuscriptDialog
                v-model="showSubmitDialog"
                :manuscript-record-id="id"
                @submitted="onSubmitted"
            />
        </q-card-actions>
        <WarnOnUnsavedChanges :is-dirty="isDirty" />
        <q-page-sticky
            v-if="
                !isScrolling && !isManuscriptReadOnly && !actionSectionVisible
            "
            position="bottom-right"
            :offset="[18, 18]"
        >
            <q-btn
                fab
                :icon="
                    isDirty
                        ? 'mdi-content-save-alert-outline'
                        : 'mdi-content-save-outline'
                "
                color="accent"
                :loading="loading"
                @click="save"
            />
        </q-page-sticky>
    </div>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import { QForm, QRejectedEntry, useQuasar } from 'quasar';
import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '../ManuscriptRecord';
import QuestionEditor from '@/components/QuestionEditor.vue';
import ContentCard from '@/components/ContentCard.vue';
import ManageManuscriptAuthorsCard from '@/models/ManuscriptAuthor/components/ManageManuscriptAuthorsCard.vue';
import RegionSelect from '@/models/Region/components/RegionSelect.vue';
import ManuscriptTypeBadge from '../components/ManuscriptTypeBadge.vue';
import ManuscriptStatusBadge from '../components/ManuscriptStatusBadge.vue';
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue';
import RequiredSpan from '@/components/RequiredSpan.vue';
import SubmitManuscriptDialog from '../components/SubmitManuscriptDialog.vue';
import WarnOnUnsavedChanges from '@/components/WarnOnUnsavedChanges.vue';
import { UtilityService } from '@/api/utils';
import ManageFundingSourcesCard from '@/models/FundingSource/components/ManageFundingSourcesCard.vue';

const $q = useQuasar();
const router = useRouter();

const generalInformationForm = ref<QForm | null>(null);

const props = defineProps<{
    id: number;
}>();

const loading = ref(true);
const manuscriptResource: Ref<ManuscriptRecordResource | null> = ref(null);
const manuscriptFile: Ref<File | null> = ref(null);
const uploadingFile = ref(false);
const manuscriptAuthorsCard = ref<InstanceType<
    typeof ManageManuscriptAuthorsCard
> | null>(null);

// watch if there is a change
const isDirty = ref(false);
const disableDirtyWatcher = ref(false);
watch(
    manuscriptResource,
    (newVal, oldValue) => {
        if (disableDirtyWatcher.value) return;
        if (
            oldValue === null ||
            manuscriptResource.value?.data.status !== 'draft'
        ) {
            return;
        }
        isDirty.value = true;
    },
    { deep: true }
);

const isManuscriptReadOnly = computed(() => {
    return manuscriptResource.value?.can?.update === false;
});

// are all required fields in the general section filled out?
const generalSectionStatus = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return 'error';
    }

    // you can't save the manuscript if the title is empty
    if (manuscript.title === '') return 'error';

    const complete =
        manuscript.title !== '' &&
        manuscript.abstract !== '' &&
        manuscript.pls !== '' &&
        manuscript.scientific_implications !== '' &&
        manuscript.relevant_to;

    return complete ? 'complete' : 'incomplete';
});

onMounted(async () => {
    await ManuscriptRecordService.find(props.id)
        .then((response) => {
            manuscriptResource.value = response;
        })
        .catch((error) => {
            if (error.status == 403) {
                $q.notify({
                    type: 'negative',
                    message:
                        'You do not have permission to view this manuscript record',
                });
                router.push({ name: 'notFound' });
            }
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
        });
    loading.value = false;
});

const { isScrolling } = useScroll(window);
const actionsSection = ref<HTMLElement | null>(null);
const actionSectionVisible = useElementVisibility(actionsSection);

const save = async () => {
    // check that the manuscriptResource is not null
    if (manuscriptResource.value === null) return;

    loading.value = true;
    await ManuscriptRecordService.save(manuscriptResource.value.data)
        .then((response) => {
            manuscriptResource.value = response;
            $q.notify({
                type: 'positive',
                color: 'primary',
                message: 'Manuscript saved successfully',
            });
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
            isDirty.value = false;
        });
};

function onFileRejected(rejectedEntries: QRejectedEntry[]): void {
    console.log(rejectedEntries);
    rejectedEntries.forEach((rejectedEntry) => {
        if (rejectedEntry.failedPropValidation === 'max-file-size') {
            $q.notify({
                type: 'negative',
                color: 'negative',
                message: 'File size is too large',
            });
        } else if (rejectedEntry.failedPropValidation === 'accept') {
            $q.notify({
                type: 'negative',
                color: 'negative',
                message: 'File type is not accepted',
            });
        }
    });
}

async function upload() {
    // if there is no manuscript file, return
    if (manuscriptFile.value === null) return;

    disableDirtyWatcher.value = true;
    uploadingFile.value = true;

    const response = await ManuscriptRecordService.attachPDF(
        manuscriptFile.value,
        props.id
    );

    if (manuscriptResource.value?.data) {
        manuscriptResource.value.data['manuscript_pdf'] =
            response.data.manuscript_pdf;
    }

    uploadingFile.value = false;
    // clear file
    manuscriptFile.value = null;

    $q.notify({
        type: 'positive',
        color: 'primary',
        message: 'File uploaded successfully',
    });

    // re-enable dirty watcher in 500 ms
    setTimeout(() => {
        disableDirtyWatcher.value = false;
    }, 500);
}

// submit the manuscript
const showSubmitDialog = ref(false);
// check if the manuscript be sent for review?
const canSubmit = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return false;
    }

    if (isDirty.value) {
        return false;
    }

    // you can't submit the manuscript if the title is empty
    if (manuscript.title === '') return false;

    // you can't submit the manuscript if the general section is incomplete
    if (generalSectionStatus.value !== 'complete') return false;

    // you can't submit the manuscript if there are no authors
    if (manuscriptAuthorsCard.value?.sectionStatus !== 'complete') return false;

    // you can't submit the manuscript if there is no manuscript pdf
    if (!manuscript.manuscript_pdf) return false;

    return true;
});

async function submit() {
    console.log('submit');
    showSubmitDialog.value = true;
}

function onSubmitted(manuscript: ManuscriptRecordResource) {
    manuscriptResource.value = manuscript;
    showSubmitDialog.value = false;
}

// display banner if the manuscript is reviewed but still needs to be published
const showPublishBanner = computed(() => {
    const manuscript = manuscriptResource.value?.data;

    if (!manuscript) {
        return false;
    }

    return (
        manuscript.status === 'reviewed' || manuscript.status === 'submitted'
    );
});

// PLS generation
const PLSLoading = ref(false);

const enablePLSPrompt = computed(() => {
    if (!manuscriptResource.value?.data) {
        console.log('no manuscript resource');
        return false;
    }

    return (
        !isManuscriptReadOnly.value &&
        (manuscriptResource.value.data.pls === '' || PLSLoading) &&
        manuscriptResource.value.data.abstract.length > 250
    );
});

const plsDisabledTooltip = computed(() => {
    if (!manuscriptResource.value?.data) {
        console.log('no manuscript resource');
        return '';
    }

    if (manuscriptResource.value.data.pls !== '') {
        return 'PLS already generated - erase it to generate a new one';
    }

    if (manuscriptResource.value.data.abstract.length < 250) {
        return 'Abstract must be at least 250 characters';
    }

    return '';
});

async function generatePLS() {
    PLSLoading.value = true;
    if (!manuscriptResource.value?.data.abstract) return;
    if (manuscriptResource.value?.data.abstract === '') return;
    manuscriptResource.value.data.pls = 'Generating PLS... please be patient';
    await UtilityService.generatePls(manuscriptResource.value.data.abstract)
        .then((response) => {
            if (!manuscriptResource.value) return;
            manuscriptResource.value.data.pls = response.data.pls;
            $q.notify({
                type: 'positive',
                color: 'primary',
                message: 'PLS generated successfully',
            });
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            PLSLoading.value = false;
        });
}
</script>

<style scoped></style>
