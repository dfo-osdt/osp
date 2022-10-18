const localeStore = useLocaleStore();
/**
 * Get the date in the current locale with preferred format for the
 * project.
 *
 * @param date
 * @returns
 */
export function useLocaleDate(date: Date | string | null) {
    return computed(() => {
        if (!date) return '';
        const locale = `${localeStore.locale}-CA`;
        return new Date(unref(date)).toLocaleDateString(locale, {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    });
}
