<script setup lang="ts">
import type { Sector } from '../Sector'
import { useSectorStore } from '@/stores/SectorStore'

withDefaults(
  defineProps<{
    readonly?: boolean
  }>(),
  {
    readonly: false,
  },
)

const sectorStore = useSectorStore()
const localeStore = useLocaleStore()

const model = defineModel<number | null>()

onMounted(() => {
  sectorStore.getSectors()
})

const options = computed(() => {
  const locale = localeStore.locale
  return (sectorStore.sectors ?? [])
    .map(sector => sector.data)
    .toSorted((a, b) => a[`name_${locale}`].localeCompare(b[`name_${locale}`]))
})

function optionLabel(option: Sector): string {
  return localeStore.locale === 'fr' ? option.name_fr : option.name_en
}
</script>

<template>
  <q-select
    v-model="model"
    :options="options"
    :option-label="optionLabel"
    :readonly="readonly"
    :label="$t('common.dfo-sector')"
    :loading="sectorStore.loading"
    option-value="id"
    emit-value
    map-options
    outlined
  />
</template>
