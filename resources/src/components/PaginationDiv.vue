<script setup lang="ts">
import type { Meta } from '@/models/Resource'

const props = defineProps<{
  modelValue: number
  meta: Readonly<Meta> | null
  disable?: boolean
}>()

const emit = defineEmits(['update:modelValue'])
const currentPage = useVModel(props, 'modelValue', emit)
</script>

<template>
  <div v-if="meta" class="row justify-between">
    <div class="text-body2 text-grey-6 q-mt-xs">
      <div v-if="meta.total >= 1">
        {{ $t('common.showing') }}: {{ meta.from }} - {{ meta.to }}
        {{ $t('common.of') }} {{ meta.total }}
      </div>
    </div>
    <q-pagination
      v-if="meta.last_page > 1"
      v-model="currentPage"
      :max="meta.last_page"
      :max-pages="6"
      :disable="disable"
      direction-links
    />
  </div>
</template>

<style scoped></style>
