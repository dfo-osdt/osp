<script setup lang="ts">
import type { MediaResource } from '../Media'
import SensitivityLabelChip from '@/components/SensitivityLabelChip.vue'

defineProps<{
  media: MediaResource
  downloadUrl: string
}>()

const emit = defineEmits<{
  delete: [media: MediaResource]
}>()

const { t } = useI18n()
</script>

<template>
  <q-item>
    <slot name="prepend" />
    <q-item-section>
      <q-item-label>
        <q-icon
          v-if="media.data.locked"
          name="mdi-lock-outline"
          color="secondary"
          class="q-mr-sm"
        />{{ media.data.file_name }}
      </q-item-label>
      <q-item-label caption>
        {{ media.data.size_bytes / 1000 }}
        {{ t('common.kb-uploaded-on') }}
        {{ new Date(media.data.created_at).toLocaleString() }}
      </q-item-label>
    </q-item-section>
    <q-item-section v-if="media.data.sensitivity_label === 'Protected A'" side>
      <SensitivityLabelChip :sensitivity="media.data.sensitivity_label" />
    </q-item-section>
    <q-item-section side>
      <span>
        <q-btn
          v-if="media.can?.delete"
          icon="mdi-delete-outline"
          color="negative"
          class="q-mr-sm"
          outline
          @click="emit('delete', media)"
        />
        <q-btn
          v-if="media.can?.download"
          icon="mdi-file-download-outline"
          color="primary"
          :href="downloadUrl"
        />
      </span>
    </q-item-section>
  </q-item>
</template>

<style scoped>

</style>
