import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface Sector {
  id: number
  name_en: string
  name_fr: string
}

export type SectorResource = Resource<Sector>
export type SectorResourceList = ResourceList<Sector>

export class SectorService {
  public static async list() {
    const response = await http.get<SectorResourceList>('/api/sectors')
    return response.data
  }
}
