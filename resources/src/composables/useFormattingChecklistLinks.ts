export function useFormattingChecklistLinks() {
  const { t } = useI18n()

  return computed(() =>
    [
      { label: t('common.english'), url: import.meta.env.VITE_FORMATTING_CHECKLIST_URL_EN || '' },
      { label: t('common.french'), url: import.meta.env.VITE_FORMATTING_CHECKLIST_URL_FR || '' },
    ].filter(link => link.url !== ''),
  )
}
