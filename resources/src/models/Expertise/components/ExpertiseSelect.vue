<script setup lang="ts">
import type { ExpertiseResource, ExpertiseResourceList } from '../Expertise';
import { QSelect } from 'quasar';
import { ExpertiseQuery, ExpertiseService } from '../Expertise';
import ExpertiseChip from './ExpertiseChip.vue';
import CreateExpertiseDialog from './CreateExpertiseDialog.vue';

const localeStore = useLocaleStore();

const expertiseSelect = ref<QSelect | null>(null);

const expertises = ref<ExpertiseResourceList>({ data: [] });
const expertiseLoading = ref(false);
const lastSearchTerm = ref('');
const showCreateExpertiseDialog = ref(false);

const modelValue = defineModel<ExpertiseResource[] | undefined>();

async function filterExpertises(
  val: string,
  update: (arg: () => Promise<void>) => void,
) {
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
}

function addExpertise(item: ExpertiseResource) {
  expertiseSelect.value?.updateInputValue('', true);
  if (!modelValue.value) {
    modelValue.value = [];
  }
  const alreadySelected = modelValue.value.some(
    (e) => e.data.id === item.data.id,
  );
  if (!alreadySelected) {
    modelValue.value = [...modelValue.value, item];
  }
  showCreateExpertiseDialog.value = false;
}

function createdExpertise(item: ExpertiseResource) {
  addExpertise(item);
}

function selectedExpertise(item: ExpertiseResource) {
  addExpertise(item);
}

function optionLabel(expertise: ExpertiseResource) {
  return localeStore.locale === 'fr'
    ? expertise.data.name_fr
    : expertise.data.name_en;
}
</script>

<template>
  <QSelect
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
        <q-separator />
        <q-item clickable @click="showCreateExpertiseDialog = true">
          <q-item-section>
            {{ $t('expertise-select.cant-find') }}
          </q-item-section>
          <q-item-section side>
            <q-btn
              icon="mdi-plus"
              color="primary"
              size="sm"
              round
              @click="showCreateExpertiseDialog = true"
            >
              <q-tooltip class="text-body2">
                {{ $t('expertise-select.add-a-new-expertise') }}
              </q-tooltip>
            </q-btn>
          </q-item-section>
        </q-item>
      </template>
    </template>
    <template #selected-item="scope">
      <ExpertiseChip
        :model-value="scope.opt"
        removable
        @remove="scope.removeAtIndex(scope.index)"
      />
    </template>
    <CreateExpertiseDialog
      v-if="showCreateExpertiseDialog"
      v-model="showCreateExpertiseDialog"
      @created="createdExpertise"
      @selected="selectedExpertise"
    />
  </QSelect>
</template>

<style scoped></style>
