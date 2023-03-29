import {
    ManagementReviewQuery,
    ManagementReviewStepResourceList,
    ManagementReviewStepService,
} from '@/models/ManagementReviewStep/ManagementReviewStep';
import { Ref } from 'vue';

/**
 * This store will be used to store the applicants recent management review steps
 */
export const useManagementReviewStepStore = defineStore(
    'ManagementReviewStepStore',
    () => {
        const loading: Ref<boolean> = ref(false);
        const managementReviewSteps: Ref<
            ManagementReviewStepResourceList | undefined
        > = ref(undefined);

        /** get values if they're not already loaded.
         * @param force - force reload of values
         * @returns void
         * */
        async function getMyManagementReviewSteps(force = false) {
            if (loading.value) return; // don't load if we're already loading
            if (managementReviewSteps.value === undefined || force) {
                loading.value = true;
                const query = new ManagementReviewQuery();
                query.sort('updated_at', 'asc');
                query.paginate(1, 10);
                managementReviewSteps.value =
                    await ManagementReviewStepService.listMy(query);
                loading.value = false;
            }
        }

        const empty = computed(() => {
            if (managementReviewSteps.value === undefined) return true;
            return managementReviewSteps.value.data.length === 0;
        });

        // returns the last 5 management review steps in reverse order (most recent update first)
        const recent = computed(() => {
            if (managementReviewSteps.value === undefined) return [];
            return managementReviewSteps.value.data
                .sort((a, b) => {
                    if (a.data.updated_at > b.data.updated_at) return -1;
                    if (a.data.updated_at < b.data.updated_at) return 1;
                    return 0;
                })
                .slice(0, 5);
        });

        const pendingReviewCount = computed(() => {
            if (managementReviewSteps.value === undefined) return 0;
            return managementReviewSteps.value.data.filter(
                (mrs) => mrs.data.status === 'pending'
            ).length;
        });

        return {
            loading,
            empty,
            recent,
            pendingReviewCount,
            getMyManagementReviewSteps,
        };
    }
);

if (import.meta.hot)
    import.meta.hot.accept(
        acceptHMRUpdate(useManagementReviewStepStore, import.meta.hot)
    );
