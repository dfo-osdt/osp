<template>
    <MainPageLayout title="Publications">
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="col-3">
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
                        ><SearchInput v-model="search" label="Filter Title"
                    /></template>
                    <template #nav>
                        <q-expansion-item
                            v-model="showFilters"
                            icon="mdi-filter-variant"
                            label="Filters"
                            :caption="filterCaption"
                        >
                            <q-card-section class="column q-gutter-md">
                                <JournalSelect
                                    ref="journalSelect"
                                    v-model="journalId"
                                    label="Journal"
                                    :disable="loading"
                                ></JournalSelect>
                                <AuthorSelect
                                    ref="authorSelect"
                                    v-model="authorId"
                                    label="Author"
                                    :disable="loading"
                                    :hide-create-author-dialog="true"
                                ></AuthorSelect>
                            </q-card-section>
                        </q-expansion-item>
                        <q-separator />
                    </template>
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
import JournalSelect from '@/models/Journal/components/JournalSelect.vue';
import AuthorSelect from '@/models/Author/components/AuthorSelect.vue';

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

    if (journalId.value) {
        query = query.filterJournalID([journalId.value]);
    }

    if (authorId.value) {
        query = query.filterAuthorID([authorId.value]);
    }

    query.sort('title', 'asc');
    query.paginate(currentPage.value, 15);

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
    active: boolean;
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
        label: 'Open Access',
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

// filters
const showFilters = ref(false);
const journalId = ref<number | null>(null);
const journalSelect = ref<InstanceType<typeof JournalSelect> | null>(null);
const authorId = ref<number | null>(null);
const authorSelect = ref<InstanceType<typeof AuthorSelect> | null>(null);

const filterCaption = computed(() => {
    let caption = '';
    if (journalId.value) {
        const { title_en } = journalSelect?.value?.selectedJournal?.data || {};
        caption += `in ${title_en} `;
    }
    if (authorId.value) {
        const { first_name, last_name } =
            authorSelect?.value?.selectedAuthor?.data || {};
        caption += `by ${first_name} ${last_name} `;
    }
    if (caption.length > 0) caption = 'Publications ' + caption.slice(0, -1);
    else caption = 'No filters applied';
    return caption;
});
</script>

<style scoped></style>
