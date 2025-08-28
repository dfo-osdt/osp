import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface Region {
  id: number
  name_en: string
  name_fr: string
  slug: string
}

export type RegionResource = Resource<Region>
export type RegionResourceList = ResourceList<Region>

export type R = RegionResource
export type RList = RegionResourceList

export class RegionService {
  public static async list() {
    const response = await http.get<RList>('api/regions')
    return response.data
  }
}
