import type { Ref } from 'vue'
import type { SectorResource } from '@/models/Sector/Sector'
import { SectorService } from '@/models/Sector/Sector'

/**
 * This store will be used to store the DFO sectors, as
 * they nearly never change, it will be cached here.
 */
export const useSectorStore = defineStore('SectorStore', () => {
  // initial state
  const loading: Ref<boolean> = ref(false)
  const sectors: Ref<SectorResource[] | undefined> = ref(undefined)

  /**
   * get values if they're not already loaded.
   *
   * @param force - force reload of values
   * @returns void
   */
  async function getSectors(force = false) {
    if (sectors.value === undefined || force) {
      loading.value = true
      try {
        const response = await SectorService.list()
        sectors.value = response.data
      }
      catch (err) {
        console.error(err)
        sectors.value = undefined
      }
      finally {
        loading.value = false
      }
    }
  }

  return {
    loading,
    sectors,
    getSectors,
  }
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useSectorStore, import.meta.hot))
}
