<template>
    <ContentCard>
        <template #title>Author(s) and Affiliation(s)</template>
        <template #title-right
            ><FormSectionStatusIcon :status="sectionStatus"
        /></template>
        <p>
            Enter all authors, their affiliations and at least one corresponding
            author. Authors will appears in the list in the order they are
            entered.
        </p>
        <q-field
            label="Authors"
            outlined
            stack-label
            bg-color="white"
            :loading="loading"
            :readonly="readonly"
            bottom-slots
            :error="!hasNoAuthors && hasNoCorrespondingAuthor"
        >
            <template #control>
                <div class="self-center full-width no-outline" tabindex="0">
                    <template v-if="publicationAuthors.data.length > 0">
                        <PublicationAuthorChip
                            v-for="item in publicationAuthors.data"
                            :key="item.data.id"
                            :publication-author="item"
                            :readonly="readonly"
                            @delete:manuscript-author="
                                deletePublicationAuthor(item)
                            "
                            @edit:toggle-corresponding-author="
                                toggleCorrespondingAuthor(item, $event)
                            "
                        />
                    </template>
                    <template v-else>
                        <span
                            >No authors identified, please add the publication
                            authors here.</span
                        >
                    </template>
                </div>
            </template>
            <template v-if="!readonly" #append>
                <q-btn
                    icon="mdi-plus"
                    color="primary"
                    size="sm"
                    round
                    :class="hasNoAuthors ? '' : 'q-mt-auto'"
                    @click="addPublicationAuthor"
                />
            </template>
            <template #error>
                <div>At least one corresponding author is required.</div>
            </template>
            <AddPublicationAuthorDialog
                v-if="showAddDialog"
                v-model="showAddDialog"
                :publication-id="publicationId"
                :current-authors="publicationAuthors.data"
                @added="addedPublicationAuthor"
            />
        </q-field>
    </ContentCard>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import { Ref } from 'vue';
import { useQuasar } from 'quasar';
import FormSectionStatusIcon from '@/components/FormSectionStatusIcon.vue';
import {
    PublicationAuthorResource,
    PublicationAuthorResourceList,
    PublicationAuthorService,
} from '@/models/PublicationAuthor/PublicationAuthor';
import AddPublicationAuthorDialog from '@/models/PublicationAuthor/components/AddPublicationAuthorDialog.vue';
import PublicationAuthorChip from '@/models/PublicationAuthor/components/PublicationAuthorChip.vue';

const $q = useQuasar();
const props = withDefaults(
    defineProps<{
        publicationId: number;
        readonly?: boolean;
    }>(),
    {
        readonly: false,
    }
);

const loading = ref(true);
const publicationAuthors: Ref<PublicationAuthorResourceList> = ref({
    data: [],
});

const hasNoAuthors = computed(() => {
    return publicationAuthors.value.data.length === 0;
});

const hasNoCorrespondingAuthor = computed(() => {
    return (
        publicationAuthors.value.data.filter(
            (item) => item.data.is_corresponding_author
        ).length === 0
    );
});

const sectionStatus = computed(() => {
    if (hasNoAuthors.value) {
        return 'incomplete';
    }
    if (hasNoCorrespondingAuthor.value) {
        return 'error';
    }
    return 'complete';
});

defineExpose({
    sectionStatus,
});

// on mounted get the manuscript authors
onMounted(async () => {
    loadPublicationAuthors();
});

const loadPublicationAuthors = async () => {
    loading.value = true;
    await PublicationAuthorService.list(props.publicationId)
        .then((response) => {
            publicationAuthors.value = response;
        })
        .catch((error) => {
            console.log(error);
        });
    loading.value = false;
};

const showAddDialog = ref(false);
const addPublicationAuthor = async () => {
    showAddDialog.value = true;
};

const addedPublicationAuthor = (
    publicationAuthor: PublicationAuthorResource
) => {
    $q.notify({
        type: 'positive',
        color: 'primary',
        message: `${
            publicationAuthor.data.author?.data.first_name ?? 'Author'
        } added successfully.`,
    });
    showAddDialog.value = false;
    loadPublicationAuthors();
};

async function toggleCorrespondingAuthor(
    item: PublicationAuthorResource,
    isCorresponding: boolean
) {
    const publicationAuthor = item.data;
    publicationAuthor.is_corresponding_author = isCorresponding;
    await PublicationAuthorService.update(publicationAuthor)
        .then(() => {
            $q.notify({
                type: 'positive',
                color: 'primary',
                message: `${
                    publicationAuthor.author?.data.first_name ?? 'Author'
                } updated successfully.`,
            });
            loadPublicationAuthors();
        })
        .catch((error) => {
            console.log(error);
        });
}

// delete manuscript author
const deletePublicationAuthor = async (
    publicationAuthor: PublicationAuthorResource
) => {
    // confirm with the user first
    $q.dialog({
        title: 'Delete author',
        message: 'Are you sure you want to delete this author?',
        cancel: true,
    }).onOk(async () => {
        await PublicationAuthorService.delete(publicationAuthor.data)
            .then(() => {
                //reload the manuscript authors
                loadPublicationAuthors();
            })
            .catch((error) => {
                console.log(error);
            });
    });
};
</script>

<style scoped></style>
