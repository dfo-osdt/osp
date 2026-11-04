/**
 * Format ORCID "fuzzy" dates ("2021", "2021-05" or "2021-05-14") as a year,
 * or a month and year, in the current locale
 */
export function useOrcidFuzzyDate() {
  const localeStore = useLocaleStore()

  function formatFuzzyDate(date: string) {
    const [year, month] = date.split('-').map(Number)
    if (!month)
      return String(year)

    return new Date(Date.UTC(year, month - 1)).toLocaleDateString(`${localeStore.locale}-CA`, {
      year: 'numeric',
      month: 'short',
      timeZone: 'UTC',
    })
  }

  return { formatFuzzyDate }
}
