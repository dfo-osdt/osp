<script
  setup
  lang="ts"
>
import type { ManuscriptRecordResource } from '../ManuscriptRecord'
import { ref } from 'vue'
import UnsubmitManuscriptDialog from './UnsubmitManuscriptDialog.vue'

const props = defineProps<{
  manuscriptRecordId: number
}>()

const emit = defineEmits<{
  unsubmitted: [manuscriptRecord: ManuscriptRecordResource]
}>()

const showDialog = ref(false)

function unsubmitted(manuscriptRecord: ManuscriptRecordResource) {
  showDialog.value = false
  emit('unsubmitted', manuscriptRecord)
}
</script>

<template>
  <div>
    <q-btn
      color="negative"
      outline
      icon="mdi-undo"
      v-bind="$attrs"
      :label="$t('manuscript-progress-view.unsubmit')"
      @click="showDialog = true"
    />

    <UnsubmitManuscriptDialog
      v-if="showDialog"
      v-model="showDialog"
      :manuscript-record-id="props.manuscriptRecordId"
      @updated="unsubmitted"
    />
  </div>
</template>
