<template>
    <q-page>
        <q-toolbar class="bg-teal-1">
            <q-icon name="mdi-view-dashboard" color="primary" size="sm" />
            <q-toolbar-title> Dashboard </q-toolbar-title>
        </q-toolbar>
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
                            <q-tab name="reviews" label="Reviews" />
                            <q-tab name="publications" label="Publications" />
                        </q-tabs>
                        <q-separator />
                    </template>

                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="manuscripts" class="bg-grey-1">
                            <div class="flex row justify-center">
                                <div
                                    class="col-12 col-lg-10 flex column text-center"
                                >
                                    <div>
                                        <q-img
                                            src="/assets/splash_images/undraw_road_sign_re_3kc3.svg"
                                            width="250px"
                                        />
                                    </div>
                                    <div
                                        class="text-h5 text-weight-light text-accent q-mb-sm"
                                    >
                                        Create your first manuscript record
                                    </div>
                                    <div
                                        class="text-body1 text-grey-8 text-left"
                                    >
                                        <p>
                                            Need to publish a primary or
                                            secondary publication? You've come
                                            to the right place. Create a
                                            manuscript record and start the
                                            process of publishing your
                                            manuscript following the national
                                            policy for science publications.
                                        </p>

                                        <p>
                                            The national policy ensures a
                                            consistent treatment of scientific
                                            publications across DFO regions,
                                            recognition of the scientific impact
                                            of the Department through the use of
                                            a standard affiliation, and
                                            effective tracking of our
                                            publications to better integrate
                                            science into departmental processes.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <q-btn
                                    rounded
                                    color="primary"
                                    @click="showCreateDialog()"
                                >
                                    Create Manuscript
                                </q-btn>
                                <create-manuscript-dialog
                                    :key="createKey"
                                    v-model="show"
                                />
                            </div>
                        </q-tab-panel>
                        <q-tab-panel name="reviews" class="bg-grey-1">
                            <div class="flex row justify-center">
                                <div
                                    class="col-12 col-lg-10 flex column text-center"
                                >
                                    <div>
                                        <q-img
                                            src="/assets/splash_images/undraw_done_checking_re_6vyx.svg"
                                            width="250px"
                                            class="q-mb-sm"
                                        />
                                    </div>
                                    <div
                                        class="text-h5 text-weight-light text-accent q-mb-sm"
                                    >
                                        You have nothing to review
                                    </div>
                                    <div class="text-body1 text-grey-8">
                                        <p>
                                            Manuscripts you've recently been
                                            asked to review will appear here.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </q-tab-panel>
                        <q-tab-panel name="publications" class="bg-grey-1">
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
                                            Manuscripts that have been reviewed
                                            will appear here. Once published,
                                            you can update the publication
                                            details. You can also add any past
                                            publication here so that it is part
                                            of our database and available for
                                            all to see.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <q-btn
                                    rounded
                                    color="primary"
                                    to="/publications/create"
                                >
                                    Add Publication
                                </q-btn>
                            </div>
                        </q-tab-panel>
                    </q-tab-panels>
                </content-card>
            </div>
        </div>
    </q-page>
</template>

<script setup lang="ts">
import MetricCard from '@/components/MetricCard.vue';
import ContentCard from '../components/ContentCard.vue';
import CreateManuscriptDialog from '@/models/auth/Manuscript/components/CreateManuscriptDialog.vue';

const tab = useStorage('dashboard-recent-tab', 'manuscripts');

// create dialog
const show = ref(false);
const createKey = ref(0);

function showCreateDialog() {
    createKey.value++;
    show.value = true;
}

const data = [
    {
        title: 'My Manuscripts',
        value: 7,
        subtitle: 'In progress',
        to: '/my-manuscripts',
    },
    {
        title: 'My Reviews',
        value: 2,
        subtitle: 'Pending',
        to: '/my-reviews',
    },
    {
        title: 'My Publications',
        value: 1,
        subtitle: 'Overdue',
        to: '/my-publications',
    },
];
</script>
