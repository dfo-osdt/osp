<template>
    <q-list separator role="list">
        <q-item
            v-for="publication in publications"
            :key="publication.data.id"
            clickable
            :to="{
                name: 'publication',
                params: { id: publication.data.id },
            }"
        >
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-primary"
                    >{{ publication.data.title }}</q-item-label
                >
                <q-item-label caption lines="2">
                    <template v-if="publication.data.publication_authors">
                        <template
                            v-if="
                                publication.data.publication_authors.length > 0
                            "
                        >
                            <span
                                v-for="(item, index) in publication.data
                                    .publication_authors"
                                :key="item.data.id"
                            >
                                <q-img
                                    v-if="item.data.author?.data.orcid"
                                    src="/assets/orcid/orcid.logo.icon.svg"
                                    width="15px"
                                />
                                {{ item.data.author?.data.last_name }},
                                {{ item.data.author?.data.first_name }}
                                <span
                                    v-if="
                                        index <
                                        publication.data.publication_authors
                                            ?.length -
                                            1
                                    "
                                    >;
                                </span>
                            </span>
                        </template>
                        <template v-else>
                            <span>{{ $t('common.no-authors') }}</span>
                        </template>
                    </template>
                </q-item-label>
                <q-item-label caption class="text-uppercase">
                    <div class="q-pt-sm flex">
                        <div>
                            {{ publishedYear(publication) }}
                        </div>
                        <div class="q-mx-sm">|</div>
                        <div>
                            <JournalNameSpan
                                :journal="publication.data.journal"
                            />
                        </div>
                        <div class="q-mx-sm">|</div>
                        <div>DOI: <DOILink :doi="publication.data.doi" /></div>
                    </div>
                </q-item-label>
            </q-item-section>
            <q-item-section side top>
                <span>
                    <PublicationStatusBadge
                        :status="publication.data.status"
                    /> </span
            ></q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import DOILink from './DoiLink.vue';
import { PublicationResource } from '../Publication';
import PublicationStatusBadge from './PublicationStatusBadge.vue';
import JournalNameSpan from '@/models/Journal/components/JournalNameSpan.vue';
const { t } = useI18n();

defineProps<{
    publications: PublicationResource[];
}>();

const publishedYear = (publication: PublicationResource) => {
    if (publication.data.published_on) {
        return new Date(publication.data.published_on).getFullYear();
    }
    return t('common.pending-publication');
};
</script>

<style scoped></style>
