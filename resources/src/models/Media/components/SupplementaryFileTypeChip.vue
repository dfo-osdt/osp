<script setup lang="ts">
import { type SupplementaryFileType, useSupplementaryFileOptions } from '../supplementaryFileOptions'

const props = defineProps<{
  type: SupplementaryFileType
}>()

const options = useSupplementaryFileOptions()

const option = computed(() => {
  const option = options.value.find(option => option.value === props.type)
  return option || undefined
})
</script>

<template>
  <q-chip
    v-if="option"
    :label="option.label()"
    color="primary"
    text-color="white"
  >
    <q-tooltip>
      {{ option.description }}
    </q-tooltip>
  </q-chip>
</template>

<style scoped>

</style>
