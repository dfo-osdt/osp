<template>
    <ContentCard>
        <template #title>Authors</template>
        <q-field
            label="Authors"
            outlined
            stack-label
            bg-color="white"
            :loading="loading"
        >
            <template #control>
                <div class="self-center full-width no-outline" tabindex="0">
                    <template v-if="manuscriptAuthors.data.length > 0">
                        <ManuscriptAuthorChip
                            v-for="item in manuscriptAuthors.data"
                            :key="item.data.id"
                            :manuscript-author="item"
                            @delete:manuscript-author="
                                deleteManuscriptAuthor(item)
                            "
                            @edit:manuscript-author="editManuscriptAuthor(item)"
                        />
                    </template>
                    <template v-else>
                        <span>No authors, please add at least one author.</span>
                    </template>
                </div>
            </template>
            <template #append>
                <q-btn
                    icon="mdi-plus"
                    color="primary"
                    size="sm"
                    round
                    :class="hasNoAuthors ? '' : 'q-mt-auto'"
                    @click="addManuscriptAuthor"
                />
            </template>
            <AddManuscriptAuthorDialog
                v-model="showAddDialog"
                :current-authors="manuscriptAuthors.data"
            />
        </q-field>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import {
    ManuscriptAuthorResource,
    ManuscriptAuthorResourceList,
    ManuscriptAuthorService,
} from '../ManuscriptAuthor';
import ManuscriptAuthorChip from './ManuscriptAuthorChip.vue';
import { useQuasar } from 'quasar';
import AddManuscriptAuthorDialog from './AddManuscriptAuthorDialog.vue';

const $q = useQuasar();
const props = defineProps<{
    manuscriptRecordId: number;
}>();

const loading = ref(true);
const manuscriptAuthors: Ref<ManuscriptAuthorResourceList> = ref({ data: [] });

const hasNoAuthors = computed(() => {
    return manuscriptAuthors.value.data.length === 0;
});

// on mounted get the manuscript authors
onMounted(async () => {
    loadManuscriptAuthors();
});

const loadManuscriptAuthors = async () => {
    loading.value = true;
    await ManuscriptAuthorService.list(props.manuscriptRecordId)
        .then((response) => {
            manuscriptAuthors.value = response;
        })
        .catch((error) => {
            console.log(error);
        });
    loading.value = false;
};

const showAddDialog = ref(false);
const addManuscriptAuthor = async () => {
    showAddDialog.value = true;
};

const editManuscriptAuthor = () => {
    console.log('edit author');
};

// delete manuscript author
const deleteManuscriptAuthor = async (
    manuscriptAuthor: ManuscriptAuthorResource
) => {
    // confirm with the user first
    $q.dialog({
        title: 'Delete author',
        message: 'Are you sure you want to delete this author?',
        cancel: true,
    }).onOk(async () => {
        await ManuscriptAuthorService.delete(manuscriptAuthor.data)
            .then(() => {
                //reload the manuscript authors
                loadManuscriptAuthors();
            })
            .catch((error) => {
                console.log(error);
            });
    });
};
</script>

<style scoped></style>
