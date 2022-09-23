<template>
    <MainPageLayout icon="mdi-file-document" :title="title">
        <ContentCard title="Manuscript Record Form" class="q-ma-md">
            <ManageManuscriptAuthorsCard
                :manuscript-record-id="id"
                class="q-ma-sm"
            />
            <ContentCard class="q-ma-sm">
                <template #title>General Information</template>
                <template v-if="manuscriptResource?.data">
                    <q-form>
                        <q-input
                            v-model="manuscriptResource.data.title"
                            outlined
                            bg-color="white"
                            class="q-mb-md"
                            label="Manuscript's Working Title"
                            :disable="loading"
                            :rules="[
                                (val) => val.length > 0 || 'Title is required',
                            ]"
                        ></q-input>

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
                                        the review of this manuscript.
                                    </p>
                                </div>
                            </div>
                            <RegionSelect
                                v-model="manuscriptResource.data.region_id"
                                label="DFO Lead Region"
                                outlined
                                :disable="loading"
                                bg-color="white"
                                class="col-12 col-lg-6"
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
                        ></QuestionEditor>
                        <QuestionEditor
                            v-model="
                                manuscriptResource.data.regions_and_species
                            "
                            title="Geographical Scope and Species"
                            :disable="loading"
                            class="q-mb-md"
                        ></QuestionEditor>
                        <QuestionEditor
                            v-model="manuscriptResource.data.relevant_to"
                            title="Relevant to programs, projects, etc."
                            :disable="loading"
                            class="q-mb-md"
                        ></QuestionEditor>
                        <QuestionEditor
                            v-model="
                                manuscriptResource.data.additional_information
                            "
                            title="Additional Information"
                            :disable="loading"
                            class="q-mb-md"
                        ></QuestionEditor>
                    </q-form>
                    <q-card-actions align="right">
                        <q-btn
                            class="q-mt-md"
                            color="primary"
                            :loading="loading"
                            :disable="!isDirty"
                            label="Save"
                            @click="save"
                        />
                    </q-card-actions>
                </template>
                <template v-else>
                    <q-skeleton type="text" />
                </template>
            </ContentCard>
            <ContentCard class="q-ma-md">
                <template #title>Manuscript</template>
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
                    label="Manuscript"
                    accept="application/pdf"
                    :loading="uploadingFile"
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
        </ContentCard>
    </MainPageLayout>
</template>

<script setup lang="ts">
import { Ref } from 'vue';
import { useQuasar } from 'quasar';
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
import ManuscriptTypeSelect from '../components/ManuscriptTypeSelect.vue';
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

    $q.notify({
        type: 'positive',
        color: 'primary',
        message: 'File uploaded successfully',
    });
}
</script>

<style scoped></style>
