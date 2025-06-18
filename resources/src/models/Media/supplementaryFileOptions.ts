import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

export type SupplementaryFileType
  = | 'manuscript_record_form'
  | 'author_agreement'
  | 'joint_copyright_agreement'
  | 'preprint'
  | 'authors_accepted_manuscript'
  | 'errata'
  | 'other'

export interface SupplementaryFileOption {
  label: () => string
  value: SupplementaryFileType
  description: string
}

export function useSupplementaryFileOptions(hideMrf = false) {
  const { t } = useI18n()

  const options = computed<SupplementaryFileOption[]>(() => [
    { label: () => t('supplementaryFileType.authorsAcceptedManuscript'), value: 'authors_accepted_manuscript', description: t('supplementaryFileType.authorsAcceptedManuscriptDescription') },
    { label: () => t('supplementaryFileType.authorAgreement'), value: 'author_agreement', description: t('supplementaryFileType.authorAgreementDescription') },
    { label: () => t('supplementaryFileType.errata'), value: 'errata', description: t('supplementaryFileType.errataDescription') },
    { label: () => t('supplementaryFileType.jointCopyrightAgreement'), value: 'joint_copyright_agreement', description: t('supplementaryFileType.jointCopyrightAgreementDescription') },
    { label: () => t('supplementaryFileType.manuscriptRecordForm'), value: 'manuscript_record_form', description: t('supplementaryFileType.manuscriptRecordFormDescription') },
    { label: () => t('supplementaryFileType.preprint'), value: 'preprint', description: t('supplementaryFileType.preprintDescription') },
    { label: () => t('supplementaryFileType.other'), value: 'other', description: t('supplementaryFileType.otherDescription') },
  ])

  if (hideMrf) {
    return computed(() => options.value.filter(option => option.value !== 'manuscript_record_form'))
  }

  return options
}
