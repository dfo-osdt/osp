<script setup lang="ts">
import type { QBadgeProps } from 'quasar'
import type { ManuscriptRecordType } from '../ManuscriptRecord'

type Props = QBadgeProps & {
  type: ManuscriptRecordType
}

const props = defineProps<Props>()

const { t } = useI18n()

const attrs = useAttrs()

const typeLabel = computed(() => {
  switch (props.type) {
    case 'primary':
      return t('manuscript.primary')
    case 'secondary':
      return t('manuscript.secondary')
    case 'preprint':
      return t('manuscript.preprint')
    default:
      return 'N/A'
  }
})
</script>

<template>
  <q-badge outline color="primary" v-bind="attrs">
    {{ typeLabel }}
  </q-badge>
</template>

<style scoped></style>
