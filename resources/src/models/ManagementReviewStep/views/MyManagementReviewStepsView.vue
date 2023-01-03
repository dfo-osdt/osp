<template>
    <MainPageLayout title="My Reviews">
        <div class="row q-gutter-lg q-col-gutter-lg flex">
            <div class="cols-2">
                <ContentCard secondary no-padding>
                    <template #title>Reviews</template>
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
                    <template
                        v-if="
                            reviews?.data.length === 0 && !hasAnyManagementSteps
                        "
                    >
                        <NoManagementStepExistsDiv />
                    </template>
                    <template
                        v-if="
                            reviews?.data.length === 0 && hasAnyManagementSteps
                        "
                    >
                        <NoResultFoundDiv />
                    </template>
                    <ManagementReviewStepList
                        v-if="reviews?.data.length"
                        :management-review-steps="reviews?.data ?? []"
                        :loading="loading"
                    ></ManagementReviewStepList>
                    <q-card-section>
                        <PaginationDiv
                            v-model="currentPage"
                            :meta="reviews?.meta ?? null"
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
import ManagementReviewStepList from '../components/ManagementReviewStepList.vue';

import PaginationDiv from '@/components/PaginationDiv.vue';
import {
    ManagementReviewQuery,
    ManagementReviewStepResourceList,
    ManagementReviewStepService,
} from '../ManagementReviewStep';
import NoManagementStepExistsDiv from '../components/NoManagementStepExistsDiv.vue';

const reviews = ref<ManagementReviewStepResourceList>();

onMounted(() => {
    getReviews();
});

const loading = ref(false);
async function getReviews() {
    if (loading.value) return;
    // build the query
    let query = new ManagementReviewQuery();

    // apply the active main filters
    mainFilterOptions.value.forEach((f) => {
        if (f.active) {
            query = f.filter(query);
        }
    });

    // is there a search term?
    if (search?.value) {
        query = query.filterManuscriptRecordTitle([search.value]);
    }

    query.sort('updated_at', 'asc');
    query.paginate(currentPage.value, 5);

    loading.value = true;
    reviews.value = await ManagementReviewStepService.listMy(query);
    loading.value = false;
}

// manuscript store - used to determine if the user has any reviews
const managementReviewStore = useManagementReviewStepStore();
managementReviewStore.getMyManagementReviewSteps();
const hasAnyManagementSteps = computed(() => {
    if (!managementReviewStore.recent) return false;
    return managementReviewStore.recent?.length > 0;
});

// type for main filter options
type MainFilterOption = {
    id: number;
    label: string;
    caption?: string;
    icon: string;
    active: boolean;
    filter: (query: ManagementReviewQuery) => ManagementReviewQuery;
};

// content filter - sidebar
const mainFilterOptions = ref<MainFilterOption[]>([
    {
        id: 1,
        label: 'All My Reviews',
        caption: 'List of all my reviews',
        icon: 'mdi-all-inclusive',
        active: true,
        filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
            return query;
        },
    },
    {
        id: 2,
        label: 'Pending',
        caption: 'I need to take action',
        icon: 'mdi-progress-clock',
        active: false,
        filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
            return query.filterStatus(['pending']);
        },
    },
    {
        id: 3,
        label: 'Completed',
        caption: 'No actions required',
        icon: 'mdi-check-circle',
        active: false,
        filter: (query: ManagementReviewQuery): ManagementReviewQuery => {
            return query.filterStatus(['deferred', 'completed']);
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
    getReviews();
};

const mainFilter = computed(() => {
    return mainFilterOptions.value.find((f) => f.active);
});

// pagination
const currentPage = ref(1);
watch(currentPage, () => {
    getReviews();
});

// search
const search = ref<string | null>(null);

watchThrottled(
    search,
    () => {
        currentPage.value = 1;
        getReviews();
    },
    { throttle: 750 }
);
</script>

<style scoped></style>