import {
    ManuscriptRecordResource,
    ManuscriptRecordService,
} from '@/models/ManuscriptRecord/ManuscriptRecord';
import { Ref } from 'vue';

/**
 * This store will be used to store the applicants recent manuscript record
 */
export const useManuscriptStore = defineStore('ManuscriptStore', () => {
    // initial state
    const loading: Ref<boolean> = ref(false);
    const manuscripts: Ref<ManuscriptRecordResource[] | undefined> =
        ref(undefined);

    /** get values if they're not already loaded.
     *
     * @param force - force reload of values
     * @returns void
     */
    async function getMyManuscripts(force = false) {
        if (loading.value) return; // don't load if we're already loading
        if (manuscripts.value === undefined || force) {
            loading.value = true;
            manuscripts.value =
                await ManuscriptRecordService.getMyManuscripts();
            loading.value = false;
        }
    }

    const empty = computed(() => {
        if (manuscripts.value === undefined) return true;
        return manuscripts.value.length === 0;
    });

    const recentManuscripts = computed(() => {
        if (manuscripts.value === undefined) return [];
        return manuscripts.value
            .sort((a, b) => {
                if (a.data.updated_at > b.data.updated_at) return -1;
                if (a.data.updated_at < b.data.updated_at) return 1;
                return 0;
            })
            .slice(0, 5);
    });

    return {
        loading,
        empty,
        recentManuscripts,
        manuscripts,
        getMyManuscripts,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(
        acceptHMRUpdate(useManuscriptStore, import.meta.hot)
    );
