import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface FunctionalArea {
  id: number
  name_en: string
  name_fr: string
}

export type FunctionalAreaResource = Resource<FunctionalArea>
export type FunctionalAreaResourceList = ResourceList<FunctionalArea>

type RList = FunctionalAreaResourceList

export class FunctionalAreaService {
  public static async list() {
    const response = await http.get<RList>('/api/functional-areas')
    return response.data
  }
}
