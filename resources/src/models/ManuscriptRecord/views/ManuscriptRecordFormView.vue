<template>
    <MainPageLayout icon="mdi-file-document" :title="title">
        <ContentCard class="q-ma-md bg-grey-1">
            <template #title>Manuscript Record Form</template>
            <template #title-right>
                <!-- Save button, icon only, caption, disabled if no changes -->
                <q-btn
                    :icon="
                        isDirty
                            ? 'mdi-content-save-alert-outline'
                            : 'mdi-content-save-outline'
                    "
                    color="primary"
                    flat
                    :loading="loading"
                    @click="save"
                >
                    <q-tooltip>Save</q-tooltip>
                </q-btn>
            </template>
            <template #nav>
                <div
                    v-if="manuscriptResource?.data"
                    class="row flex justify-between q-mx-md q-my-sm"
                >
                    <div class="text-subtitle1 text-weight-light">
                        Manuscript Type:
                        <ManuscriptTypeBadge
                            :type="manuscriptResource.data.type"
                            class="text-body2 text-weight-light"
                            :outline="false"
                        />
                    </div>
                    <div class="text-subtitle1 text-weight-light">
                        Status:
                        <ManuscriptStatusBadge
                            :status="manuscriptResource.data.status"
                            class="text-body2 text-weight-light"
                            :outline="false"
                        />
                    </div>
                </div>

                <q-separator />
            </template>
            <ManageManuscriptAuthorsCard
                :manuscript-record-id="id"
                class="q-ma-sm"
            />
            <ContentCard class="q-ma-sm">
                <template #title>General Information</template>
                <template v-if="manuscriptResource?.data">
                    <q-form>
                        <div class="q-mb-md">
                            <div class="q-ml-xs">
                                <div
                                    class="text-body1 text-primary text-weight-medium"
                                >
                                    Manuscript's Working Title
                                </div>
                                <div class="q-my-xs">
                                    <p>
                                        Enter to the manuscript's working title.
                                        You will be able to modify the title
                                        when you update this manuscript status
                                        to published.
                                    </p>
                                </div>
                            </div>
                            <q-input
                                v-model="manuscriptResource.data.title"
                                outlined
                                bg-color="white"
                                label="Manuscript's Working Title"
                                :disable="loading"
                                :rules="[
                                    (val) =>
                                        val.length > 0 || 'Title is required',
                                ]"
                            ></q-input>
                        </div>

                        <div class="q-mb-lg">
                            <div class="q-ml-xs">
                                <div
                                    class="text-body1 text-primary text-weight-medium"
                                >
                                    Lead Region
                                </div>
                                <div class="q-my-xs">
                                    <p>
                                        Select the DFO region responsible for
                                        the review of this manuscript. If more
                                        than one region is responsible, select
                                        the region that will lead the review.
                                    </p>
                                </div>
                            </div>
                            <RegionSelect
                                v-model="manuscriptResource.data.region_id"
                                label="DFO Lead Region"
                                outlined
                                :disable="loading"
                                bg-color="white"
                            />
                        </div>

                        <QuestionEditor
                            v-model="manuscriptResource.data.abstract"
                            title="Abstract"
                            :disable="loading"
                            class="q-mb-lg"
                        >
                            <p>Copy your manuscript's abstract here.</p>
                        </QuestionEditor>
                        <QuestionEditor
                            v-model="manuscriptResource.data.pls"
                            title="Plain Language Summary"
                            :disable="loading"
                            class="q-mb-md"
                        >
                            <p>
                                Plain Language Summary (PLS) of the manuscript
                                to explain the relevance of the science to the
                                Department and to promote the value of science
                                through outreach and communication. It is
                                intended for internal use to identify linkages
                                to programs, policies and stakeholders and for
                                use in the plain language promotion of science
                            </p>
                        </QuestionEditor>
                        <QuestionEditor
                            v-model="
                                manuscriptResource.data.scientific_implications
                            "
                            title="Scientific Implications"
                            :disable="loading"
                            class="q-mb-md"
                        >
                            <p>
                                Describe the scientifc implications of the
                                paper. (i.e. field, importance, focus, purpose,
                                benefits, etc.)
                            </p>
                        </QuestionEditor>
                        <QuestionEditor
                            v-model="
                                manuscriptResource.data.regions_and_species
                            "
                            title="Geographical Scope and Species"
                            :disable="loading"
                            class="q-mb-md"
                        >
                            <p>
                                Describe the geographical scope/region and (if
                                applicable) species (to include common names) of
                                the paper.
                            </p>
                        </QuestionEditor>
                        <QuestionEditor
                            v-model="manuscriptResource.data.relevant_to"
                            title="Relevant to programs, projects, etc."
                            :disable="loading"
                            class="q-mb-md"
                        >
                            <p>
                                Describe how the paper is relevant to programs,
                                projects, acts, initiatives, etc. (i.e. how it
                                supports the department's mandate, how it
                                supports the department's strategic plan, how it
                                supports regional priorities, etc.)
                            </p>
                        </QuestionEditor>
                        <QuestionEditor
                            v-model="
                                manuscriptResource.data.additional_information
                            "
                            title="Additional Information of Importance"
                            :disable="loading"
                            class="q-mb-md"
                        >
                            <p>
                                Provide any additional information of importance
                                to the manuscript.
                            </p>
                        </QuestionEditor>
                    </q-form>
                </template>
                <template v-else>
                    <q-skeleton type="text" />
                </template>
            </ContentCard>
            <ContentCard class="q-mx-sm q-mt-md">
                <template #title>Attach Manuscript</template>
                <p>
                    Upload the most recent copy of your manuscript as a PDF.
                    This file can be updated as required.
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
                                            manuscriptResource.data
                                                .manuscript_pdf.size_bytes /
                                            1000
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
                    v-model="manuscriptFile"
                    outlined
                    use-chips
                    :label="
                        manuscriptResource?.data.manuscript_pdf
                            ? 'Upload new version of manuscript'
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
            <q-card-actions align="right">
                <q-btn
                    class="q-mt-md"
                    color="primary"
                    :loading="loading"
                    label="Save"
                    @click="save"
                />
                <q-btn
                    class="q-mt-md"
                    color="primary"
                    :loading="loading"
                    :disable="true"
                    label="Submit"
                    @click="submit"
                />
            </q-card-actions>
        </ContentCard>
    </MainPageLayout>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import { QRejectedEntry, useQuasar } from 'quasar';
import { onBeforeRouteLeave } from 'vue-router';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import {
    ManuscriptRecordService,
    ManuscriptRecordResource,
} from '../ManuscriptRecord';
import QuestionEditor from '@/components/QuestionEditor.vue';
import ContentCard from '@/components/ContentCard.vue';
import ManageManuscriptAuthorsCard from '@/models/ManuscriptAuthor/components/ManageManuscriptAuthorsCard.vue';
import RegionSelect from '@/models/Region/components/RegionSelect.vue';
import ManuscriptTypeBadge from '../components/ManuscriptTypeBadge.vue';
import ManuscriptStatusBadge from '../components/ManuscriptStatusBadge.vue';
const { t } = useI18n();
const $q = useQuasar();

const props = defineProps<{
    id: number;
}>();

const loading = ref(true);
const manuscriptResource: Ref<ManuscriptRecordResource | null> = ref(null);
const manuscriptFile: Ref<File | null> = ref(null);
const uploadingFile = ref(false);

onMounted(async () => {
    await ManuscriptRecordService.find(props.id)
        .then((response) => {
            manuscriptResource.value = response;
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
        });
    loading.value = false;
});

// watch if there is a change
const isDirty = ref(false);

watch(
    manuscriptResource,
    (newVal, oldValue) => {
        if (oldValue === null) {
            return;
        }
        isDirty.value = true;
    },
    { deep: true }
);

// warn the user if they try to leave the page while there are unsaved changes
onBeforeRouteLeave((to, from, next) => {
    if (isDirty.value) {
        $q.dialog({
            title: t('Unsaved Changes'),
            message: t(
                'You have unsaved changes. Are you sure you want to leave?'
            ),
            cancel: true,
            persistent: true,
        }).onOk(() => {
            next();
        });
    } else {
        next();
    }
});

const title = computed(
    () =>
        'Manuscript: ' + `${manuscriptResource.value?.data.title}` ??
        t('common.loading')
);

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

    const dirtyState = isDirty.value;
    console.log(dirtyState);
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
    console.log(dirtyState);
    isDirty.value = dirtyState;

    // clear file
    manuscriptFile.value = null;

    $q.notify({
        type: 'positive',
        color: 'primary',
        message: 'File uploaded successfully',
    });
}

async function submit() {
    console.log('submit');
}
</script>

<style scoped></style>
