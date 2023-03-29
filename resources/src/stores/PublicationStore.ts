import {
    PublicationResource,
    PublicationService,
} from '@/models/Publication/Publication';
import { Ref } from 'vue';

/**
 * This store will be used to store the applicants recent manuscript record
 */
export const usePublicationStore = defineStore('PublicationStore', () => {
    // initial state
    const loading: Ref<boolean> = ref(false);
    const publications: Ref<PublicationResource[] | undefined> = ref(undefined);

    /** get values if they're not already loaded.
     *
     * @param force - force reload of values
     * @returns void
     */
    async function getMyPublications(force = false) {
        if (loading.value) return; // don't load if we're already loading
        if (publications.value === undefined || force) {
            loading.value = true;
            const response = await PublicationService.getMyPublications();
            publications.value = response.data;
            loading.value = false;
        }
    }

    const empty = computed(() => {
        if (publications.value === undefined) return true;
        return publications.value.length === 0;
    });

    const recentPublications = computed(() => {
        if (publications.value === undefined) return [];
        return publications.value
            .sort((a, b) => {
                if (a.data.updated_at > b.data.updated_at) return -1;
                if (a.data.updated_at < b.data.updated_at) return 1;
                return 0;
            })
            .slice(0, 5);
    });

    /**
     * Get the number of publications that have an accepted status
     */
    const overduePublications = computed(() => {
        if (publications.value === undefined) return 0;
        return publications.value.filter((p) => p.data.status === 'accepted')
            .length;
    });

    return {
        loading,
        empty,
        recentPublications,
        publications,
        getMyPublications,
        overduePublications,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(
        acceptHMRUpdate(usePublicationStore, import.meta.hot)
    );
