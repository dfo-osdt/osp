<script setup lang="ts">
import type { SupplementaryFileOption, SupplementaryFileType } from '../supplementaryFileOptions'
import { useSupplementaryFileOptions } from '../supplementaryFileOptions'

const props = defineProps<{
  hideMrf?: boolean
}>()

const model = defineModel<SupplementaryFileType | null>()
const options = useSupplementaryFileOptions(props.hideMrf)
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
    :option-label="(item) => item.label()"
    :label="t('common.document-type')"
    outlined
    @update:model-value="syncModel"
  >
    <template #option="{ opt, itemProps }">
      <q-item v-bind="itemProps">
        <q-item-section>
          <q-item-label>{{ opt.label() }}</q-item-label>
          <q-item-label caption lines="2">
            {{ opt.description }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
  </q-select>
</template>

<style scoped />
