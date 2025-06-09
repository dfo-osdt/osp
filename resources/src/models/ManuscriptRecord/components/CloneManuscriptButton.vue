<script
    setup
    lang="ts"
    generic="
        M extends ManuscriptRecordResource | ManuscriptRecordSummaryResource
    "
>
import type { QBtnProps } from 'quasar'
import type { ManuscriptRecordResource, ManuscriptRecordSummaryResource } from '../ManuscriptRecord'
import CloneManuscriptDialog from './CloneManuscriptDialog.vue'

type Props = QBtnProps & {
  manuscript: M
}

const props = defineProps<Props>()

const attrs = useAttrs()

const showCloneDialog = ref(false)

function openCloneDialog(): void {
  showCloneDialog.value = true
}
</script>

<template>
  <q-btn
    v-bind="{ ...attrs, ...props }"
    :aria-label="$t('common.clone')"
    @click.stop="openCloneDialog()"
  >
    <q-tooltip>{{ $t('common.clone') }}</q-tooltip>
  </q-btn>

  <CloneManuscriptDialog
    v-if="showCloneDialog"
    v-model="showCloneDialog"
    :source-manuscript="props.manuscript.data"
  />
</template>

<style scoped></style>
