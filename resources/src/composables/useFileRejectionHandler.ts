import type { QRejectedEntry } from 'quasar'
import { useQuasar } from 'quasar'
import { useI18n } from 'vue-i18n'

export function useFileRejectionHandler() {
  const $q = useQuasar()
  const { t } = useI18n()

  function onFileRejected(rejectedEntries: QRejectedEntry[]): void {
    console.error(rejectedEntries)
    rejectedEntries.forEach((rejectedEntry) => {
      if (rejectedEntry.failedPropValidation === 'max-file-size') {
        $q.notify({
          type: 'negative',
          color: 'negative',
          message: t('common.validation.file-size-is-too-large'),
        })
      }
      else if (rejectedEntry.failedPropValidation === 'accept') {
        $q.notify({
          type: 'negative',
          color: 'negative',
          message: t('common.validation.file-type-is-not-accepted'),
        })
      }
    })
  }

  return { onFileRejected }
}
