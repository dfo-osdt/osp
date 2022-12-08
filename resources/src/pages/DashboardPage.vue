<template>
    <main-page-layout icon="mdi-view-dashboard" title="Dashboard">
        <div class="q-pa-md">
            <div class="row">
                <div
                    v-for="card in data"
                    :key="card.title"
                    class="col-12 col-sm-4 q-pa-sm"
                >
                    <metric-card
                        :title="card.title"
                        :value="card.value"
                        :subtitle="card.subtitle"
                        :to="card.to"
                    />
                </div>
            </div>
            <div class="q-pa-sm">
                <content-card>
                    <template #title>
                        <q-icon
                            name="mdi-history"
                            color="accent"
                            size="sm"
                            left
                        />
                        <span>Recent</span>
                    </template>
                    <template #nav>
                        <q-tabs
                            v-model="tab"
                            dense
                            narrow-indicator
                            align="left"
                            indicator-color="primary"
                            active-color="primary"
                            class="text-grey-8"
                        >
                            <q-tab name="manuscripts" label="Manuscripts" />
                            <q-tab name="reviews">
                                <span class="text-weight-medium q-pr-xs"
                                    >Reviews</span
                                >
                                <q-badge
                                    v-if="reviewSteps.pendingReviewCount > 0"
                                    color="primary"
                                    rounded
                                    floating
                                    transparent
                                    >{{
                                        reviewSteps.pendingReviewCount
                                    }}</q-badge
                                >
                            </q-tab>
                            <q-tab name="publications" label="Publications" />
                        </q-tabs>
                        <q-separator />
                    </template>

                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="manuscripts" class="q-pa-none">
                            <template
                                v-if="manuscripts.empty && !manuscripts.loading"
                            >
                                <NoManuscriptExistsDiv
                                    title="Create your first manuscript record"
                                />
                            </template>
                            <ManuscriptList
                                v-if="!manuscripts.empty"
                                class="q-mb-lg"
                                :manuscripts="manuscripts.recentManuscripts"
                            />
                            <div class="text-center">
                                <q-btn
                                    rounded
                                    color="primary"
                                    class="q-mb-md"
                                    @click="showCreateManuscript = true"
                                >
                                    Create Manuscript
                                </q-btn>
                                <create-manuscript-dialog
                                    v-if="showCreateManuscript"
                                    v-model="showCreateManuscript"
                                />
                            </div>
                        </q-tab-panel>
                        <q-tab-panel name="reviews" class="q-pa-none">
                            <RecentManagementReviewStepsView />
                        </q-tab-panel>
                        <q-tab-panel name="publications" class="q-pa-none">
                            <template v-if="publications.empty">
                                <div class="flex row justify-center">
                                    <div
                                        class="col-12 col-lg-10 flex column text-center"
                                    >
                                        <div>
                                            <q-img
                                                src="/assets/splash_images/undraw_scientist_0ft9.svg"
                                                width="250px"
                                                class="q-mb-sm"
                                            />
                                        </div>
                                        <div
                                            class="text-h5 text-weight-light text-accent q-mb-sm"
                                        >
                                            Add a publication
                                        </div>
                                        <div
                                            class="text-body1 text-grey-8 text-left"
                                        >
                                            <p>
                                                Manuscripts that have been
                                                reviewed will appear here. Once
                                                published, you can update the
                                                publication details. You can
                                                also add any past publication
                                                here so that it is part of our
                                                database and available for all
                                                to see.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <PublicationList
                                v-if="!publications.empty"
                                class="q-mb-lg"
                                :publications="publications.recentPublications"
                            />
                            <div class="text-center q-mb-md">
                                <q-btn
                                    rounded
                                    color="primary"
                                    @click="showCreatePublication = true"
                                >
                                    Add Publication
                                </q-btn>
                            </div>
                            <CreatePublicationDialog
                                v-if="showCreatePublication"
                                v-model="showCreatePublication"
                                @created="publicationCreated"
                            />
                        </q-tab-panel>
                    </q-tab-panels>
                </content-card>
            </div>
        </div>
    </main-page-layout>
</template>

<script setup lang="ts">
import MetricCard from '@/components/MetricCard.vue';
import ContentCard from '../components/ContentCard.vue';
import CreateManuscriptDialog from '@/models/ManuscriptRecord/components/CreateManuscriptDialog.vue';
import MainPageLayout from '@/layouts/MainPageLayout.vue';
import ManuscriptList from '@/models/ManuscriptRecord/components/ManuscriptList.vue';
import RecentManagementReviewStepsView from '@/models/ManagementReviewStep/views/RecentManagementReviewStepsView.vue';
import PublicationList from '@/models/Publication/components/PublicationList.vue';
import CreatePublicationDialog from '@/models/Publication/components/CreatePublicationDialog.vue';
import { PublicationResource } from '@/models/Publication/Publication';
import NoManuscriptExistsDiv from '@/models/ManuscriptRecord/components/NoManuscriptExistsDiv.vue';

const manuscripts = useManuscriptStore();
const reviewSteps = useManagementReviewStepStore();
const publications = usePublicationStore();

const tab = useStorage('dashboard-recent-tab', 'manuscripts');

// load the latest manuscripts
onMounted(() => {
    manuscripts.getMyManuscripts(true);
    reviewSteps.getMyManagementReviewSteps(true);
    publications.getMyPublications(true);
});

const data = computed(() => [
    {
        title: 'My Manuscripts',
        value: manuscripts.inProgressManuscripts.length,
        subtitle: 'In progress',
        to: '/my-manuscripts',
    },
    {
        title: 'My Reviews',
        value: reviewSteps.pendingReviewCount,
        subtitle: 'Pending',
        to: '/my-reviews',
    },
    {
        title: 'My Publications',
        value: publications.overduePublications,
        subtitle: 'Awaiting Publication',
        to: '/my-publications',
    },
]);

// manuscript section
const showCreateManuscript = ref(false);

// publication section
const showCreatePublication = ref(false);

/**
 * Closes the dialog and adds the created publication
 * to the publication store.
 *
 * @param publication the publication that was created
 */
const publicationCreated = (publication: PublicationResource) => {
    if (publications.publications) publications.publications.push(publication);
    showCreatePublication.value = false;
};
</script>
