<template>
    <q-select
        ref="journalSelect"
        v-model="selectedJournal"
        :options="journals.data"
        :option-value="optionValue"
        :option-label="optionLabel"
        clearable
        :label="$t('common.journal')"
        :loading="journalsLoading"
        use-input
        stack-label
        outlined
        :hint="$t('journal-select.search-hint')"
        :hide-hint="isReadOnly"
        @filter="filterJournals"
        @clear="selectedJournal = null"
    >
        <template #no-option>
            <q-item>
                <q-item-section class="text-grey">
                    <template v-if="lastSearchTerm === ''">
                        {{ $t('journal-select.search-hint') }}
                    </template>
                    <template v-else>
                        {{ $t('common.no-results-found-for') }}
                        <strong>{{ lastSearchTerm }}</strong>
                    </template>
                </q-item-section>
            </q-item>
            <q-separator />
            <q-item>
                <q-item-section>
                    {{ $t('journal-select.not-found-msg') }}
                </q-item-section>
            </q-item>
        </template>
        <template #option="{ itemProps, opt }">
            <q-item v-bind="itemProps">
                <q-item-section>
                    <q-item-label>{{ getJournalName(opt) }}</q-item-label>
                    <q-item-label caption>
                        {{ opt.data.publisher }}
                    </q-item-label>
                </q-item-section>
            </q-item>
        </template>
    </q-select>
</template>

<script setup lang="ts">
import { SpatieQuery } from '@/api/SpatieQuery';
import { QSelect } from 'quasar';
import {
    JournalResource,
    JournalResourceList,
    JournalService,
} from '../Journal';

const journalSelect = ref<QSelect | null>(null);

const props = defineProps<{
    modelValue: number | null;
}>();

const journals = ref<JournalResourceList>({ data: [] });
const selectedJournal = ref<JournalResource | null>(null);
const lastSearchTerm = ref('');
const journalsLoading = ref(false);
const isReadOnly = computed(
    () => journalSelect.value?.$props.readonly ?? false
);

const emit = defineEmits(['update:modelValue']);
watch(selectedJournal, (journal) => {
    if (journal) {
        emit('update:modelValue', journal.data.id);
    } else {
        emit('update:modelValue', null);
    }
});

onMounted(async () => {
    if (props.modelValue) {
        const journal = await JournalService.get(props.modelValue);
        selectedJournal.value = journal;
    }
});

const filterJournals = async (
    val: string,
    update: (arg: () => Promise<void>) => void
) => {
    lastSearchTerm.value = val;
    update(async () => {
        if (val !== '') {
            const needle = val.toLowerCase();
            journalsLoading.value = true;

            const query = new SpatieQuery();
            query.filter('search', needle).paginate(1, 10);

            await JournalService.list(query).then((response) => {
                // order response by length of title
                response.data.sort((a, b) => {
                    return a.data.title_en.length - b.data.title_en.length;
                });

                journals.value = response;
            });
            journalsLoading.value = false;
        }
    });
};

function optionValue(item: JournalResource) {
    return item.data.id;
}
function optionLabel(item: JournalResource) {
    const { title_en, publisher } = item.data;
    return `${title_en} (${publisher})`;
}

defineExpose({
    selectedJournal,
});

// locale
const localeStore = useLocaleStore();
function getJournalName(journal: JournalResource) {
    const { title_en, title_fr } = journal.data;
    if (localeStore.locale === 'fr') {
        return title_fr ?? title_en;
    }
    return title_en;
}
</script>

<style scoped></style>
