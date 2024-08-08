import type { OrganizationResource } from '../Organization/Organization'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface Funder {
  readonly id: number
  name_en: string
  name_fr: string
  organization_id: string | null
  organization?: OrganizationResource
}

export type FunderResource = Resource<Funder>
export type FunderResourceList = ResourceList<Funder>

export class FunderService {
  private static baseURL = 'api/funders'

  /**
   * Get a funder.
   *
   * @param id The funder ID.
   * @returns The funder.
   */
  public static async find(id: number) {
    const response = await http.get<FunderResource>(
            `${this.baseURL}/${id}`,
    )
    return response.data
  }

  /** Get all funders */
  public static async all() {
    const response = await http.get<FunderResourceList>(this.baseURL)
    return response.data
  }
}
