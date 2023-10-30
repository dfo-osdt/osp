<template>
    <q-select
        ref="expertiseSelect"
        v-model="modelValue"
        :options="expertises.data"
        use-input
        :hint="$t('expertise-select.hint')"
        stack-label
        :option-label="optionLabel"
        outlined
        multiple
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
            <expertise-chip
                :model-value="scope.opt"
                removable
                @remove="scope.removeAtIndex(scope.index)"
            />
        </template>
    </q-select>
</template>

<script setup lang="ts">
import { QSelect } from 'quasar';
import {
    ExpertiseQuery,
    ExpertiseResource,
    ExpertiseResourceList,
    ExpertiseService,
} from '../Expertise';
import ExpertiseChip from './ExpertiseChip.vue';

const localeStore = useLocaleStore();

const expertiseSelect = ref<QSelect | null>(null);

const expertises = ref<ExpertiseResourceList>({ data: [] });
const expertiseLoading = ref(false);
const lastSearchTerm = ref('');

const modelValue = defineModel<ExpertiseResource[] | undefined>();

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

function optionLabel(expertise: ExpertiseResource) {
    return localeStore.locale === 'fr'
        ? expertise.data.name_fr
        : expertise.data.name_en;
}
</script>

<style scoped></style>
