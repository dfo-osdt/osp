<script setup lang="ts">
import type { AuthorResource } from '../Author'
import ContentCard from '@/components/ContentCard.vue'
import ExpertiseChip from '@/models/Expertise/components/ExpertiseChip.vue'

const props = defineProps<{
  author: AuthorResource
}>()

const { t } = useI18n()
const localeStore = useLocaleStore()
const locale = computed(() => localeStore.locale)

const sortedExpertises = computed(() => {
  if (!props.author.data.expertises)
    return []

  return [...props.author.data.expertises].sort((a, b) => {
    const nameA = a.data[`name_${locale.value}`] || a.data.name_en
    const nameB = b.data[`name_${locale.value}`] || b.data.name_en
    return nameA.localeCompare(nameB)
  })
})
</script>

<template>
  <ContentCard secondary class="q-mt-lg">
    <template #title>
      <q-icon name="mdi-book-education-outline" size="md" class="q-mr-sm" />
      {{ t('author-profile.expertise') }}
    </template>

    <template v-if="sortedExpertises.length === 0">
      <div class="text-grey-7 text-center q-py-md">
        <q-icon name="mdi-book-outline" size="lg" class="q-mb-sm" />
        <div>{{ t('author-profile.no-expertise') }}</div>
      </div>
    </template>

    <template v-else>
      <p class="text-body2 text-grey-7 q-mb-md">
        {{ t('author-profile.expertise-description') }}
      </p>

      <div class="row q-gutter-xs">
        <ExpertiseChip
          v-for="expertise in sortedExpertises"
          :key="expertise.data.id"
          :model-value="expertise"
          readonly
        />
      </div>
    </template>
  </ContentCard>
</template>

<style scoped></style>
