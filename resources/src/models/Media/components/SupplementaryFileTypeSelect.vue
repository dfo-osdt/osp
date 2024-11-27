<script setup lang="ts">
import type { SupplementaryFileOption, SupplementaryFileType } from '../supplementaryFileOptions'
import { useSupplementaryFileOptions } from '../supplementaryFileOptions'

const model = defineModel<SupplementaryFileType | null>()
const options = useSupplementaryFileOptions()
const localModel = ref<SupplementaryFileOption | null>(null)
const { t } = useI18n()

onMounted(() => {
  if (model.value) {
    localModel.value = options.value.find(option => option.value === model.value) || null
  }
})

watch(model, (value) => {
  localModel.value = options.value.find(option => option.value === value) || null
})

function syncModel(item: SupplementaryFileOption) {
  model.value = item.value
}
</script>

<template>
  <q-select
    v-model="localModel"
    :options="options"
    :option-label="item => item.label()"
    :label="t('common.document-type')"
    outlined
    @update:model-value="syncModel"
  />
</template>

<style scoped />
