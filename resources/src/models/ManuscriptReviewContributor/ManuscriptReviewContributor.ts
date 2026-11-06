import type { AuthorResource } from '../Author/Author'
import type { Resource, ResourceList } from '../Resource'
import type { ReviewContributorRole } from './reviewContributorRoleOptions'
import { http } from '@/api/http'

export interface ManuscriptReviewContributor {
  readonly id: number
  manuscript_record_id: number
  author_id: number
  role: ReviewContributorRole
  readonly orcid_synced: boolean
  author?: AuthorResource
}

export type ManuscriptReviewContributorResource = Resource<ManuscriptReviewContributor>
export type ManuscriptReviewContributorResourceList = ResourceList<ManuscriptReviewContributor>

type R = ManuscriptReviewContributorResource
type RList = ManuscriptReviewContributorResourceList

/**
 * Manuscript Review Contributor Service
 */
export class ManuscriptReviewContributorService {
  /**
   * Get a list of manuscript review contributors
   * @param manuscriptRecordId
   */
  public static async list(manuscriptRecordId: number) {
    const response = await http.get<RList>(
      `api/manuscript-records/${manuscriptRecordId}/review-contributors`,
    )
    return response.data
  }

  /**
   *  Add a manuscript review contributor
   * @param manuscriptRecordId - manuscript to add this contributor to
   * @param authorId - author to associate with this manuscript
   * @param role - what the contributor did for this manuscript
   */
  public static async create(
    manuscriptRecordId: number,
    authorId: number,
    role: ReviewContributorRole,
  ) {
    const url = `api/manuscript-records/${manuscriptRecordId}/review-contributors`
    const response = await http.post<any, R>(
      url,
      { author_id: authorId, role },
    )
    return response.data
  }

  /**
   * Change the role of a manuscript review contributor
   * @param manuscriptReviewContributor
   */
  public static async update(manuscriptReviewContributor: ManuscriptReviewContributor) {
    const { manuscript_record_id, id } = manuscriptReviewContributor
    const url = `api/manuscript-records/${manuscript_record_id}/review-contributors/${id}`
    const response = await http.put<any, R>(url, {
      role: manuscriptReviewContributor.role,
    })
    return response.data
  }

  /**
   * Delete a manuscript review contributor
   * @param manuscriptReviewContributor
   */
  public static async delete(manuscriptReviewContributor: ManuscriptReviewContributor) {
    const { manuscript_record_id, id } = manuscriptReviewContributor
    const response = await http.delete(
      `api/manuscript-records/${manuscript_record_id}/review-contributors/${id}`,
    )
    return response.status === 204
  }
}
