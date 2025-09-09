<script
  setup
  lang="ts"
  generic="M extends ManuscriptRecordResource | ManuscriptRecordSummaryResource"
>
import type { QBtnProps } from 'quasar'
import type {
  ManuscriptRecordResource,
  ManuscriptRecordSummaryResource,
} from '../ManuscriptRecord'
import { useQuasar } from 'quasar'
import { useI18n } from 'vue-i18n'
import { ManuscriptRecordService } from '../ManuscriptRecord'

const props = defineProps<
  QBtnProps & {
    manuscript: M
  }
>()
const emit = defineEmits<{
  (e: 'deleted', manuscript: M): void
}>()
const { t } = useI18n()
const $q = useQuasar()

function confirmDeletion(): void {
  $q.dialog({
    title: t('dialog.delete-manuscript.title'),
    message: t('dialog.delete-manuscript.message', {
      title: props.manuscript.data.title,
    }),
    cancel: true,
    persistent: false,
  }).onOk(() => {
    deleteManuscript()
  })
}

async function deleteManuscript() {
  const deleted = await ManuscriptRecordService.delete(
    props.manuscript.data.id,
  )
  if (deleted) {
    $q.notify({
      message: t('dialog.delete-manuscript.manuscript-deleted'),
      type: 'positive',
      icon: 'mdi-check',
    })
    emit('deleted', props.manuscript)
  }
}
</script>

<template>
  <q-btn
    v-bind="props"
    :aria-label="$t('common.delete')"
    @click.stop="confirmDeletion()"
  >
    <slot>
      <q-tooltip>{{ $t('common.delete') }}</q-tooltip>
    </slot>
  </q-btn>
</template>

<style scoped></style>
