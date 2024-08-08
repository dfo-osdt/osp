<script setup lang="ts">
import type { FunctionalArea } from '../FunctionalArea'
import { useFunctionalAreaStore } from '@/stores/FunctionalAreaStore'

const functionalAreaStore = useFunctionalAreaStore()
const localeStore = useLocaleStore()

const model = defineModel<number | null>()

onMounted(() => {
  functionalAreaStore.getFunctionalAreas()
})

const options = computed(() => {
  const functionalAreas: FunctionalArea[] = []
  functionalAreaStore.functionalAreas?.forEach((functionalArea) => {
    functionalAreas.push(functionalArea.data)
  })

  // sort
  const locale = localeStore.locale
  functionalAreas.sort((a, b) => {
    return a[`name_${locale}`].localeCompare(b[`name_${locale}`])
  })

  return functionalAreas
})

function optionLabel(option: FunctionalArea): string {
  return localeStore.locale === 'fr' ? option.name_fr : option.name_en
}
</script>

<template>
  <q-select
    v-model="model"
    :options="options"
    :option-label="optionLabel"
    option-value="id"
    emit-value
    outlined
    map-options
  />
</template>
