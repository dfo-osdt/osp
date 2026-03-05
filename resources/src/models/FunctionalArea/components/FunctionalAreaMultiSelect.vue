<script setup lang="ts">
import type { FunctionalArea } from '../FunctionalArea'
import { useFunctionalAreaStore } from '@/stores/FunctionalAreaStore'

defineProps<{
  label?: string
  disable?: boolean
}>()

const functionalAreaStore = useFunctionalAreaStore()
const localeStore = useLocaleStore()

const model = defineModel<number[] | null>()

onMounted(() => {
  functionalAreaStore.getFunctionalAreas()
})

const options = computed(() => {
  const functionalAreas: FunctionalArea[] = []
  functionalAreaStore.functionalAreas?.forEach((functionalArea) => {
    functionalAreas.push(functionalArea.data)
  })

  const locale = localeStore.locale
  functionalAreas.sort((a, b) => {
    return a[`name_${locale}`].localeCompare(b[`name_${locale}`])
  })

  return functionalAreas
})

/** Resolve IDs to full objects for q-select internal model */
const selected = computed<FunctionalArea[]>({
  get() {
    if (!model.value?.length) {
      return []
    }
    return options.value.filter(o => model.value!.includes(o.id))
  },
  set(val) {
    model.value = val.length ? val.map(o => o.id) : null
  },
})

function optionLabel(option: FunctionalArea): string {
  return localeStore.locale === 'fr' ? option.name_fr : option.name_en
}
</script>

<template>
  <q-select
    v-model="selected"
    :options="options"
    :option-label="optionLabel"
    option-value="id"
    multiple
    clearable
    use-chips
    outlined
    :label="label"
    :disable="disable"
  />
</template>
