<template>
    <MainPageLayout title="My Manuscripts">
        <template
            v-if="authorStore.user?.can('create_manuscript_records')"
            #toolbar
        >
            <div class="flex justify-end">
                <q-fab
                    v-model="showCreateManuscriptDialog"
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
                            Create Manuscript</q-tooltip
                        >
                    </template>
                </q-fab>
            </div>
        </template>
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary no-padding>
                    <template #title>Manuscripts</template>
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
                        v-if="
                            authorStore.user?.can('create_manuscript_records')
                        "
                        class="flex-center flex q-mt-lg q-mb-sm q-mx-md"
                    >
                        <q-btn
                            label="Create manuscript"
                            color="primary"
                            outline
                            icon="mdi-plus"
                            @click="showCreateManuscriptDialog = true"
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
                        v-if="manuscripts.length === 0 && !hasAnyManuscripts"
                    >
                        <NoManuscriptExistsDiv
                            title="No manuscripts exists or are shared with you"
                        />
                    </template>
                    <template
                        v-if="manuscripts.length === 0 && hasAnyManuscripts"
                    >
                        <NoResultFoundDiv />
                    </template>
                    <ManuscriptList :manuscripts="manuscripts" />
                </ContentCard>
            </div>
        </div>
        <CreateManuscriptDialog v-model="showCreateManuscriptDialog" />
    </MainPageLayout>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue';
import SearchInput from '@/components/SearchInput.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import CreateManuscriptDialog from '../components/CreateManuscriptDialog.vue';
import ManuscriptList from '../components/ManuscriptList.vue';
import NoResultFoundDiv from '@/components/NoResultsFoundDiv.vue';
import NoManuscriptExistsDiv from '../components/NoManuscriptExistsDiv.vue';

import {
    ManuscriptQuery,
    ManuscriptRecordService,
    ManuscriptRecordSummaryResource,
} from '../ManuscriptRecord';

const manuscripts = ref<ManuscriptRecordSummaryResource[]>([]);

onMounted(() => {
    getManuscripts();
});

async function getManuscripts() {
    // build the query
    let query = new ManuscriptQuery();

    // apply the active main filters
    mainFilterOptions.value.forEach((f) => {
        if (f.active) {
            query = f.filter(query);
        }
    });

    // is there a search term?
    if (search.value) {
        query = query.filterTitle([search.value]);
    }

    query.sort('title', 'asc');
    query.paginate(currentPage.value, 10);

    manuscripts.value = await ManuscriptRecordService.getMyManuscripts(query);
}

// create manuscript dialog
const showCreateManuscriptDialog = ref(false);

// user store
const authorStore = useAuthStore();

// manuscript store - used to determine if the user has any manuscripts
const manuscriptStore = useManuscriptStore();
manuscriptStore.getMyManuscripts();
const hasAnyManuscripts = computed(() => {
    if (!manuscriptStore.manuscripts) return false;
    return manuscriptStore.manuscripts?.length > 0;
});

// type for main filter options
type MainFilterOption = {
    id: number;
    label: string;
    caption?: string;
    icon: string;
    active: boolean;
    filter: (query: ManuscriptQuery) => ManuscriptQuery;
};

// content filter - sidebar
const mainFilterOptions = ref<MainFilterOption[]>([
    {
        id: 1,
        label: 'All Manuscripts',
        caption: 'Includes ones shared with me',
        icon: 'mdi-all-inclusive',
        active: true,
        filter: (query: ManuscriptQuery): ManuscriptQuery => {
            return query;
        },
    },
    {
        id: 2,
        label: 'My Manuscripts',
        caption: 'I am the applicant',
        icon: 'mdi-account-arrow-left-outline',
        active: false,
        filter: (query: ManuscriptQuery): ManuscriptQuery => {
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
        filter: (query: ManuscriptQuery): ManuscriptQuery => {
            return query.filterStatus([
                'draft',
                'in_review',
                'reviewed',
                'submitted',
            ]);
        },
    },
    {
        id: 4,
        label: 'Completed',
        caption: 'No actions required',
        icon: 'mdi-check-circle',
        active: false,
        filter: (query: ManuscriptQuery): ManuscriptQuery => {
            return query.filterStatus(['accepted', 'withdrawn', 'withheld']);
        },
    },
]);

const mainFilterClick = (filterId: number) => {
    // clear search filter
    search.value = '';
    mainFilterOptions.value = mainFilterOptions.value.map((f) => {
        f.active = f.id === filterId;
        return f;
    });
    getManuscripts();
};

const mainFilter = computed(() => {
    return mainFilterOptions.value.find((f) => f.active);
});

// pagination
const currentPage = ref(1);

// search
const search = ref<string | null>(null);

watchThrottled(
    search,
    () => {
        getManuscripts();
    },
    { throttle: 750 }
);
</script>

<style scoped></style>
