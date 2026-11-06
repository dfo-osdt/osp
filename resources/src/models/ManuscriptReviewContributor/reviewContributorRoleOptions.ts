import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

/**
 * ORCID peer review roles that apply to our reports
 */
export type ReviewContributorRole = 'reviewer' | 'editor'

export interface ReviewContributorRoleOption {
  label: string
  value: ReviewContributorRole
}

export function useReviewContributorRoleOptions() {
  const { t } = useI18n()

  const options = computed<ReviewContributorRoleOption[]>(() => [
    {
      label: t('review-contributor-role.reviewer'),
      value: 'reviewer',
    },
    {
      label: t('review-contributor-role.editor'),
      value: 'editor',
    },
  ])

  function roleLabel(role: ReviewContributorRole): string {
    return options.value.find(option => option.value === role)?.label ?? role
  }

  return { options, roleLabel }
}
