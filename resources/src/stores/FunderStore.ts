import { FunderResource, FunderService } from '@/models/Funder/Funder';
import { Ref } from 'vue';

/**
 * This store will be used to store the application's funders, as
 * they nearly never change, it will be cached here.
 */
export const useFunderStore = defineStore('FunderStore', () => {
    // initial state
    const loading: Ref<boolean> = ref(false);
    const funders: Ref<FunderResource[] | undefined> = ref(undefined);

    /** get values if they're not already loaded.
     *
     * @param force - force reload of values
     * @returns void
     */
    async function getFunders(force = false) {
        if (funders.value === undefined || force) {
            loading.value = true;
            try {
                funders.value = (await FunderService.all()).data;
            } catch (err) {
                console.error(err);
                funders.value = undefined;
            }
            loading.value = false;
        }
    }

    return {
        loading,
        funders,
        getFunders,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useFunderStore, import.meta.hot));
