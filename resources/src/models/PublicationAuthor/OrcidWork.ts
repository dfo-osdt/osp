import type { JournalResource } from '../Journal/Journal'
import type { Resource } from '../Resource'
import { http } from '@/api/http'

/**
 * Where a publication stands with the author's ORCID record: 'removing' is on
 * ORCID but should no longer be (declined, or no longer eligible) until the delete runs
 */
export type OrcidWorkStatus = 'not_on_orcid' | 'pending' | 'on_orcid' | 'removing' | 'declined'

/**
 * A publication of the author, as listed to them for their ORCID works
 */
export interface OrcidWork {
  readonly id: number
  orcid_status: OrcidWorkStatus
  publication: {
    id: number
    title: string
    doi: string
    published_on: string | null
    journal: JournalResource
  }
}

export type OrcidWorkResource = Resource<OrcidWork>

export interface OrcidWorkResourceList {
  data: OrcidWorkResource[]
  meta: {
    can_write_to_orcid: boolean
    auto_sync: boolean
  }
}

/**
 * The authenticated user's publications that can be on their ORCID record as works
 */
export class OrcidWorkService {
  public static async list() {
    const response = await http.get<OrcidWorkResourceList>(
      'api/user/orcid/works',
    )
    return response.data
  }

  /**
   * Add the publication to the author's ORCID record, now or once they connect ORCID
   */
  public static async add(id: number) {
    const response = await http.post<null, OrcidWorkResource>(
      `api/user/orcid/works/${id}/add`,
    )
    return response.data
  }

  /**
   * Remove the publication from the author's ORCID record and never add it again unless they ask
   */
  public static async remove(id: number) {
    const response = await http.post<null, OrcidWorkResource>(
      `api/user/orcid/works/${id}/remove`,
    )
    return response.data
  }

  /**
   * Add every publication the author has not decided on yet, leaving declined ones off
   */
  public static async addAll() {
    const response = await http.post<null, OrcidWorkResourceList>(
      'api/user/orcid/works/add-all',
    )
    return response.data
  }

  /**
   * Add publications to ORCID as soon as they are published, from now on
   */
  public static async updateAutoSync(autoSync: boolean) {
    const response = await http.put<{ auto_sync: boolean }, { auto_sync: boolean }>(
      'api/user/orcid/works/auto-sync',
      { auto_sync: autoSync },
    )
    return response.data
  }
}
