<script setup lang="ts">
import type { ExpertiseResource, ExpertiseSimilarMatch } from '../Expertise'
import BaseDialog from '@/components/BaseDialog.vue'
import { ExpertiseService } from '../Expertise'

const props = withDefaults(
  defineProps<{
    initialSearch?: string
  }>(),
  {
    initialSearch: '',
  },
)

const emit = defineEmits<{
  (event: 'created', payload: ExpertiseResource): void
  (event: 'selected', payload: ExpertiseResource): void
}>()

const localeStore = useLocaleStore()
const { t } = useI18n()

const name_en = ref(localeStore.locale === 'en' ? props.initialSearch : '')
const name_fr = ref(localeStore.locale === 'fr' ? props.initialSearch : '')
const loading = ref(false)
const similarLoading = ref(false)
const similarMatches = ref<ExpertiseSimilarMatch[]>([])
const debounceTimer = ref<ReturnType<typeof setTimeout> | null>(null)

function debouncedSearch() {
  if (debounceTimer.value) {
    clearTimeout(debounceTimer.value)
  }
  debounceTimer.value = setTimeout(async () => {
    await searchSimilar()
  }, 400)
}

async function searchSimilar() {
  if (!name_en.value && !name_fr.value) {
    similarMatches.value = []
    return
  }
  similarLoading.value = true
  try {
    const response = await ExpertiseService.similar(
      name_en.value,
      name_fr.value,
    )
    similarMatches.value = response.data
  }
  catch {
    similarMatches.value = []
  }
  finally {
    similarLoading.value = false
  }
}

function selectExisting(match: ExpertiseSimilarMatch) {
  emit('selected', match.expertise)
}

async function createExpertise() {
  loading.value = true
  try {
    const expertise = await ExpertiseService.create({
      name_en: name_en.value,
      name_fr: name_fr.value,
    })
    emit('created', expertise)
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  if (props.initialSearch) {
    searchSimilar()
  }
})

function matchLabel(match: ExpertiseSimilarMatch) {
  return localeStore.locale === 'fr'
    ? match.expertise.data.name_fr
    : match.expertise.data.name_en
}

function matchSecondaryLabel(match: ExpertiseSimilarMatch) {
  return localeStore.locale === 'fr'
    ? match.expertise.data.name_en
    : match.expertise.data.name_fr
}
</script>

<template>
  <BaseDialog persistent :title="t('create-expertise-dialog.title')">
    <q-card-section>
      <p>
        {{ t('create-expertise-dialog.p1') }}
      </p>
      <p>
        {{ t('create-expertise-dialog.p2') }}
      </p>
      <q-separator />
    </q-card-section>
    <q-form @submit="createExpertise">
      <q-input
        v-model="name_en"
        outlined
        :label="t('create-expertise-dialog.expertise-name-english')"
        class="q-ma-md"
        :rules="[(val: string) => val !== '' || t('common.required')]"
        @update:model-value="debouncedSearch"
      />
      <q-input
        v-model="name_fr"
        outlined
        :label="t('create-expertise-dialog.expertise-name-french')"
        class="q-ma-md"
        :rules="[(val: string) => val !== '' || t('common.required')]"
        @update:model-value="debouncedSearch"
      />

      <q-card-section v-if="similarLoading">
        <q-spinner size="sm" class="q-mr-sm" />
        {{ t('create-expertise-dialog.checking-similar') }}
      </q-card-section>

      <q-card-section v-if="similarMatches.length > 0">
        <div class="text-subtitle2 q-mb-sm">
          {{ t('create-expertise-dialog.similar-found') }}
        </div>
        <q-list bordered separator>
          <q-item
            v-for="match in similarMatches"
            :key="match.expertise.data.id"
            clickable
            @click="selectExisting(match)"
          >
            <q-item-section>
              <q-item-label>{{ matchLabel(match) }}</q-item-label>
              <q-item-label caption>
                {{ matchSecondaryLabel(match) }}
              </q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-badge color="primary" :label="`${match.score}%`" />
            </q-item-section>
          </q-item>
        </q-list>
        <div class="text-caption text-grey q-mt-sm">
          {{ t('create-expertise-dialog.click-to-select') }}
        </div>
      </q-card-section>

      <div class="flex justify-end">
        <q-btn
          color="primary"
          :label="t('common.create')"
          type="submit"
          class="q-ma-md"
          :loading="loading"
        />
      </div>
    </q-form>
  </BaseDialog>
</template>

<style scoped></style>
