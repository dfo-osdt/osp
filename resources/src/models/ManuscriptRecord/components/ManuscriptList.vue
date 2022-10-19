<template>
    <q-list separator>
        <q-item
            v-for="manuscript in manuscripts"
            :key="manuscript.data.id"
            clickable
            :to="{
                name: 'manuscript.form',
                params: { id: manuscript.data.id },
            }"
        >
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-accent"
                    >{{ manuscript.data.title }}</q-item-label
                >
                <q-item-label caption>
                    <template v-if="manuscript.data.manuscript_authors">
                        <template
                            v-if="manuscript.data.manuscript_authors.length > 0"
                        >
                            <span
                                v-for="(item, index) in manuscript.data
                                    .manuscript_authors"
                                :key="item.data.id"
                            >
                                <q-img
                                    v-if="item.data.author?.data.orcid"
                                    src="/assets/orcid.logo.icon.svg"
                                    width="15px"
                                />
                                {{ item.data.author?.data.last_name }},
                                {{ item.data.author?.data.first_name }}
                                <span v-if="item?.data.organization">
                                    ({{ item.data.organization.data.abbr_en }})
                                    <q-tooltip>{{
                                        item.data.organization.data.name_en
                                    }}</q-tooltip>
                                </span>
                                <span
                                    v-if="
                                        index <
                                        manuscript.data.manuscript_authors
                                            ?.length -
                                            1
                                    "
                                    >;
                                </span>
                            </span>
                        </template>
                        <template v-else>
                            <span>No authors</span>
                        </template>
                    </template>
                </q-item-label>
            </q-item-section>
            <q-item-section side top>
                <span>
                    <manuscript-type-badge
                        :type="manuscript.data.type"
                        class="q-mr-xs"
                    />
                    <manuscript-status-badge
                        :status="manuscript.data.status"
                    /> </span
            ></q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import { ManuscriptRecordResource } from '../ManuscriptRecord';
import ManuscriptTypeBadge from './ManuscriptTypeBadge.vue';
import ManuscriptStatusBadge from './ManuscriptStatusBadge.vue';

defineProps<{
    manuscripts: ManuscriptRecordResource[];
}>();
</script>

<style scoped></style>
