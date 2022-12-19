<template>
    <MainPageLayout title="My Publications">
        <template v-if="authorStore.user?.can('create_publications')" #toolbar>
            <div class="flex justify-end">
                <q-fab
                    v-model="showCreatePublicationDialog"
                    color="primary"
                    icon="mdi-plus"
                    padding="md"
                    class="absolute-top-right q-mr-lg q-mt-lg"
                >
                    <template #tooltip>
                        <q-tooltip
                            class="text-body2"
                            anchor="center left"
                            self="center right"
                        >
                            Create Publication</q-tooltip
                        >
                    </template>
                </q-fab>
            </div>
        </template>
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary no-padding>
                    <template #title>Publications</template>
                    <q-list class="text-body1">
                        <q-item
                            v-for="f in mainFilterOptions"
                            :key="f.id"
                            v-ripple
                            clickable
                            :active="f.active"
                            @click="mainFilterClick(f.id)"
                        >
                            <q-item-section avatar>
                                <q-icon :name="f.icon" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{ f.label }}</q-item-label>
                                <q-item-label caption>
                                    {{ f.caption }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                    <div
                        v-if="authorStore.user?.can('create_publications')"
                        class="flex-center flex q-mt-lg q-mb-sm q-mx-md"
                    >
                        <q-btn
                            label="Create publication"
                            color="primary"
                            outline
                            icon="mdi-plus"
                            @click="showCreatePublicationDialog = true"
                        />
                    </div>
                </ContentCard>
            </div>
            <div class="col q-pr-lg">
                <ContentCard secondary>
                    <template #title>{{ mainFilter?.label }}</template>
                    <template #subtitle>{{ mainFilter?.caption }}</template>
                    <template #title-right
                        ><SearchInput v-model="search" label="Filter"
                    /></template>
                    <template
                        v-if="
                            publications?.data.length === 0 &&
                            !hasAnyPublications
                        "
                    >
                        <NoPublicationsExistDiv title="No publications found" />
                    </template>
                    <template
                        v-if="
                            publications?.data.length === 0 &&
                            hasAnyPublications
                        "
                    >
                        <NoResultFoundDiv />
                    </template>
                    <PublicationList :publications="publications?.data ?? []" />
                    <q-card-section>
                        <PaginationDiv
                            v-model="currentPage"
                            :meta="publications?.meta ?? null"
                            :disable="loading"
                        ></PaginationDiv>
                    </q-card-section>
                </ContentCard>
            </div>
        </div>
        <CreatePublicationDialog v-model="showCreatePublicationDialog" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import SearchInput from '@/components/SearchInput.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue';
import NoPublicationsExistDiv from '../components/NoPublicationExistDiv.vue';
import PaginationDiv from '@/components/PaginationDiv.vue';
import PublicationList from '../components/PublicationList.vue';
import {
    PublicationQuery,
    PublicationResourceList,
    PublicationService,
} from '../Publication';
import CreatePublicationDialog from '../components/CreatePublicationDialog.vue';

const publications = ref<PublicationResourceList>();

onMounted(() => {
    getPublications();
});

const loading = ref(false);
async function getPublications() {
    if (loading.value) return;
    // build the query
    let query = new PublicationQuery();

    // apply the active main filters
    mainFilterOptions.value.forEach((f) => {
        if (f.active) {
            query = f.filter(query);
        }
    });

    // is there a search term?
    if (search?.value) {
        query = query.filterTitle(search.value);
    }

    query.sort('title', 'asc');
    query.paginate(currentPage.value, 5);

    loading.value = true;
    publications.value = await PublicationService.getMyPublications(query);
    loading.value = false;
}

// create manuscript dialog
const showCreatePublicationDialog = ref(false);

// user store
const authorStore = useAuthStore();

// manuscript store - used to determine if the user has any manuscripts
const publicationStore = usePublicationStore();
publicationStore.getMyPublications();
const hasAnyPublications = computed(() => {
    if (!publicationStore.publications) return false;
    return publicationStore.publications?.length > 0;
});

// type for main filter options
type MainFilterOption = {
    id: number;
    label: string;
    caption?: string;
    icon: string;
    active: boolean;
    filter: (query: PublicationQuery) => PublicationQuery;
};

// content filter - sidebar
const mainFilterOptions = ref<MainFilterOption[]>([
    {
        id: 1,
        label: 'All Publications',
        caption: 'Includes ones shared with me',
        icon: 'mdi-all-inclusive',
        active: true,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query;
        },
    },
    {
        id: 2,
        label: 'My Publications',
        caption: 'I am responsible for this publication record',
        icon: 'mdi-account-arrow-left-outline',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return authorStore.user
                ? query.filterUserId([authorStore.user.id])
                : query;
        },
    },
    {
        id: 3,
        label: 'In Progress',
        caption: 'Actions still required',
        icon: 'mdi-progress-clock',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query.filterStatus(['accepted']);
        },
    },
    {
        id: 4,
        label: 'Published',
        caption: 'No actions required',
        icon: 'mdi-check-circle',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query.filterStatus(['published']);
        },
    },
]);

const mainFilterClick = (filterId: number) => {
    mainFilterOptions.value = mainFilterOptions.value.map((f) => {
        f.active = f.id === filterId;
        return f;
    });
    search.value = '';
    currentPage.value = 1;
    getPublications();
};

const mainFilter = computed(() => {
    return mainFilterOptions.value.find((f) => f.active);
});

// pagination
const currentPage = ref(1);
watch(currentPage, () => {
    getPublications();
});

// search
const search = ref<string | null>(null);

watchThrottled(
    search,
    () => {
        currentPage.value = 1;
        getPublications();
    },
    { throttle: 750 }
);
</script>

<style scoped></style>
