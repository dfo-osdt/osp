<template>
    <MainPageLayout title="Publications">
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
                </ContentCard>
            </div>
            <div class="col q-pr-lg">
                <ContentCard secondary>
                    <template #title>{{ mainFilter?.label }}</template>
                    <template #subtitle>{{ mainFilter?.caption }}</template>
                    <template #title-right
                        ><SearchInput v-model="search" label="Filter"
                    /></template>
                    <template v-if="publications?.data.length === 0">
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
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import SearchInput from '@/components/SearchInput.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue';
import PaginationDiv from '@/components/PaginationDiv.vue';
import PublicationList from '../components/PublicationList.vue';
import {
    PublicationQuery,
    PublicationResourceList,
    PublicationService,
} from '../Publication';

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
    publications.value = await PublicationService.list(query);
    loading.value = false;
}

// type for main filter options
type MainFilterOption = {
    id: number;
    label: string;
    caption?: string;
    icon: string;
    active: boolean | (() => boolean);
    filter: (query: PublicationQuery) => PublicationQuery;
};

// content filter - sidebar
const mainFilterOptions = ref<MainFilterOption[]>([
    {
        id: 1,
        label: 'All Publications',
        caption: 'All published publications',
        icon: 'mdi-all-inclusive',
        active: true,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query;
        },
    },
    {
        id: 2,
        label: 'Open Access Publications',
        caption: 'Publications published as open access',
        icon: 'mdi-lock-open-outline',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query.filterOpenAccess();
        },
    },
    {
        id: 3,
        label: 'Under Embargo',
        caption: 'Publications under embargo',
        icon: 'mdi-calendar-clock-outline',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query.filterUnderEmbargo();
        },
    },
    {
        id: 4,
        label: 'Secondary Publications',
        caption: 'Published in DFO journals',
        icon: 'mdi-bank-outline',
        active: false,
        filter: (query: PublicationQuery): PublicationQuery => {
            return query.filterSecondaryPublication();
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
