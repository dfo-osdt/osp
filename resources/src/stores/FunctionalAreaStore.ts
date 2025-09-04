import type { Ref } from 'vue'
import type {
  FunctionalAreaResource,
} from '@/models/FunctionalArea/FunctionalArea'
import {
  FunctionalAreaService,
} from '@/models/FunctionalArea/FunctionalArea'

/**
 * This store will be used to store the application's regions, as
 * they nearly never change, it will be cached here.
 */
export const useFunctionalAreaStore = defineStore('FunctionalAreaStore', () => {
  // initial state
  const loading: Ref<boolean> = ref(false)
  const functionalAreas: Ref<FunctionalAreaResource[] | undefined>
    = ref(undefined)

  /**
   * get values if they're not already loaded.
   *
   * @param force - force reload of values
   * @returns void
   */
  async function getFunctionalAreas(force = false) {
    if (functionalAreas.value === undefined || force) {
      loading.value = true
      try {
        const response = await FunctionalAreaService.list()
        functionalAreas.value = response.data
      }
      catch (err) {
        console.error(err)
        functionalAreas.value = undefined
      }
      finally {
        loading.value = false
      }
    }
  }

  return {
    loading,
    functionalAreas,
    getFunctionalAreas,
  }
})

if (import.meta.hot) {
  import.meta.hot.accept(
    acceptHMRUpdate(useFunctionalAreaStore, import.meta.hot),
  )
}
