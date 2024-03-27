<template>
    <q-list separator>
        <q-item
            v-for="manuscript in manuscripts"
            :key="manuscript.data.id"
            clickable
            @click="goToManuscript(manuscript)"
        >
            <q-item-section>
                <q-item-label
                    class="text-body1 text-weight-medium text-accent"
                    >{{ manuscript.data.title }}</q-item-label
                >
                <q-item-label caption lines="2">
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
                                    src="/assets/orcid/orcid.logo.icon.svg"
                                    width="15px"
                                />
                                {{ item.data.author?.data.last_name }},
                                {{ item.data.author?.data.first_name }}
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
                            <span>{{ $t('common.no-authors') }}</span>
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
                    <manuscript-status-badge :status="manuscript.data.status" />
                    <delete-manuscript-button
                        v-if="manuscript.can?.delete"
                        flat
                        size="sm"
                        color="red"
                        dense
                        icon="mdi-delete-outline"
                        class="q-ml-sm"
                        :manuscript="manuscript"
                        @deleted="$emit('deleted', manuscript)"
                    />
                </span>
            </q-item-section>
        </q-item>
    </q-list>
</template>

<script setup lang="ts">
import { ManuscriptRecordSummaryResource } from '../ManuscriptRecord';
import ManuscriptTypeBadge from './ManuscriptTypeBadge.vue';
import ManuscriptStatusBadge from './ManuscriptStatusBadge.vue';
import DeleteManuscriptButton from './DeleteManuscriptButton.vue';
const router = useRouter();

defineProps<{
    manuscripts: ManuscriptRecordSummaryResource[];
}>();

defineEmits<{
    (e: 'deleted', manuscript: ManuscriptRecordSummaryResource): void;
}>();

function goToManuscript(manuscript: ManuscriptRecordSummaryResource) {
    router.push({
        name: 'manuscript.form',
        params: { id: manuscript.data.id },
    });
}
</script>

<style scoped></style>
