import type { Ref } from 'vue'
import type { Region, RegionResource } from '@/models/Region/Region'
import { RegionService } from '@/models/Region/Region'

/**
 * This store will be used to store the application's regions, as
 * they nearly never change, it will be cached here.
 */
export const useRegionStore = defineStore('RegionStore', () => {
  // initial state
  const loading: Ref<boolean> = ref(false)
  const regions: Ref<Region[] | undefined> = ref(undefined)

  /**
   * get values if they're not already loaded.
   *
   * @param force - force reload of values
   * @returns void
   */
  async function getRegions(force = false) {
    if (regions.value === undefined || force) {
      loading.value = true
      await RegionService.list()
        .then(({ data }) => {
          const regionsData = data.map((region: RegionResource) => {
            return region.data
          })
          regions.value = regionsData
        })
        .catch((err) => {
          console.error(err)
          regions.value = undefined
        })
        .finally(() => {
          loading.value = false
        })
    }
  }

  return {
    loading,
    regions,
    getRegions,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useRegionStore, import.meta.hot))
