<template>
    <MainPageLayout icon="mdi-file-document" :title="title">
        <ContentCard class="q-ma-md">
            <template #title>Authors</template>
            <pre>{{ manuscriptResource?.data?.manuscript_authors }}</pre>
        </ContentCard>
        <ContentCard class="q-ma-md">
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
                    <QuestionEditor
                        v-model="manuscriptResource.data.abstract"
                        title="Abstract"
                        :disable="loading"
                        class="q-mb-md"
                    ></QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.pls_en"
                        title="Plain Language Summary - English"
                        :disable="loading"
                        class="q-mb-md"
                    ></QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.pls_fr"
                        title="Plain Language Summary - French"
                        :disable="loading"
                        class="q-mb-md"
                    ></QuestionEditor>
                    <QuestionEditor
                        v-model="
                            manuscriptResource.data.scientific_implications
                        "
                        title="Scientific Implications"
                        :disable="loading"
                        class="q-mb-md"
                    ></QuestionEditor>
                    <QuestionEditor
                        v-model="manuscriptResource.data.regions_and_species"
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
                        v-model="manuscriptResource.data.additional_information"
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
const { t } = useI18n();
const $q = useQuasar();

const props = defineProps<{
    id: number;
}>();

const loading = ref(true);
const manuscriptResource: Ref<ManuscriptRecordResource | null> = ref(null);

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
            console.log('nothing to save here');
            return;
        }
        isDirty.value = true;
        console.log('change..');
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
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loading.value = false;
            isDirty.value = false;
        });
};
</script>

<style scoped></style>
