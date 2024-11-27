<script setup lang="ts">
import type { MediaResource } from '../Media'
import SensitivityLabelChip from '@/components/SensitivityLabelChip.vue'
import SupplementaryFileTypeChip from './SupplementaryFileTypeChip.vue'

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
      <q-item-label v-if="media.data.description" class="q-ml-none">
        <q-icon name="mdi-text" color="accent" size="sm" />
        {{ media.data.description }}
      </q-item-label>
    </q-item-section>
    <q-item-section side>
      <div>
        <SensitivityLabelChip v-if="media.data.sensitivity_label === 'Protected A'" :sensitivity="media.data.sensitivity_label" />
        <SupplementaryFileTypeChip v-if="media.data.supplementary_file_type" outline :type="media.data.supplementary_file_type" />
        <slot name="side" />
      </div>
    </q-item-section>
    <q-item-section v-if="media.data.supplementary_file_type" side />
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
          icon="mdi-file-download-outline"
          color="primary"
          :disabled="!media.can?.download"
          :href="media.can?.download ? downloadUrl : undefined"
        >
          <q-tooltip>
            {{ t('common.cant-download') }}
          </q-tooltip></q-btn></span>
    </q-item-section>
  </q-item>
</template>

<style scoped>

</style>
