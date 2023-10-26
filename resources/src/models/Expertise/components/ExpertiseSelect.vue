<template>
    <q-select
        v-model="selectedExpertise"
        :options="expertises.data"
        use-input
        :hint="$t('expertise-select.hint')"
        stack-label
        :option-label="optionLabel"
        outlined
        multiple
        clearable
        :loading="expertiseLoading"
        @filter="filterExpertises"
    >
        <template #no-option>
            <template v-if="lastSearchTerm === ''">
                <q-item>
                    <q-item-section class="text-grey">
                        {{ $t('expertise-select.hint') }}
                    </q-item-section>
                </q-item>
            </template>
            <template v-else>
                <q-item>
                    <q-item-section class="text-grey">
                        {{ $t('common.no-results-found-for') }}
                        <strong>{{ lastSearchTerm }}</strong>
                    </q-item-section>
                </q-item>
            </template>
        </template>
        <template #selected-item="scope">
            <expertise-chip :model-value="scope.opt" removable />
        </template>
    </q-select>
</template>

<script setup lang="ts">
import {
    ExpertiseQuery,
    ExpertiseResource,
    ExpertiseResourceList,
    ExpertiseService,
} from '../Expertise';
import ExpertiseChip from './ExpertiseChip.vue';

const localeStore = useLocaleStore();

const expertises = ref<ExpertiseResourceList>({ data: [] });
const selectedExpertise = ref<ExpertiseResource | null>(null);
const expertiseLoading = ref(false);
const lastSearchTerm = ref('');

const filterExpertises = async (
    val: string,
    update: (arg: () => Promise<void>) => void,
) => {
    lastSearchTerm.value = val;
    update(async () => {
        if (val !== '') {
            const needle = val.toLowerCase();
            expertiseLoading.value = true;

            const query = new ExpertiseQuery();
            query
                .when(
                    localeStore.locale === 'fr',
                    (query) => query.filterNameFr(needle),
                    (query) => query.filterNameEn(needle),
                )
                .sortByNameLength(localeStore.locale)
                .paginate(1, 100);

            expertises.value = await ExpertiseService.list(query);
            expertiseLoading.value = false;
        }
    });
};

function optionValue(expertise: ExpertiseResource) {
    return expertise.data.id;
}

function optionLabel(expertise: ExpertiseResource) {
    return localeStore.locale === 'fr'
        ? expertise.data.name_fr
        : expertise.data.name_en;
}
</script>

<style scoped></style>
