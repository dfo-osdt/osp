const localeStore = useLocaleStore()
/**
 * Get the date in the current locale with preferred format for the
 * project. If the data is a string, it must be in ISO format.
 *
 * @param date
 * @returns
 */
export function useLocaleDate(date: Date | string | null) {
  return computed(() => {
    date = unref(date)
    if (!date)
      return ''
    const locale = `${localeStore.locale}-CA`

    // check if date only - no time. If that is the case, Date will
    // treat is a UTC instead of local so let's add a time to it.
    // is date a string?
    if (typeof date === 'string') {
      // is it a date only string?
      if (date.length === 10) {
        // add a time to it
        date = `${date} 00:00:00`
      }
    }

    return new Date(date).toLocaleDateString(locale, {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  })
}
