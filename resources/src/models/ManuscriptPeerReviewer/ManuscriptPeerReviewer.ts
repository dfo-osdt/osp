import type { AuthorResource } from '../Author/Author'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface ManuscriptPeerReviewer {
  readonly id: number
  manuscript_record_id: number
  author_id: number
  author?: AuthorResource
}

export type ManuscriptPeerReviewerResource = Resource<ManuscriptPeerReviewer>
export type ManuscriptPeerReviewerResourceList = ResourceList<ManuscriptPeerReviewer>

type R = ManuscriptPeerReviewerResource
type RList = ManuscriptPeerReviewerResourceList

/**
 * Manuscript Peer Reviewer Service
 */
export class ManuscriptPeerReviewerService {
  /**
   * Get a list of manuscript peer reviewers
   * @param manuscriptRecordId
   */
  public static async list(manuscriptRecordId: number) {
    const response = await http.get<RList>(
      `api/manuscript-records/${manuscriptRecordId}/peer-reviewers`,
    )
    return response.data
  }

  /**
   *  Add a manuscript peer reviewer
   * @param manuscriptRecordId - manuscript to add this peer reviewer to
   * @param authorId - author to associate with this manuscript
   */
  public static async create(
    manuscriptRecordId: number,
    authorId: number,
  ) {
    const url = `api/manuscript-records/${manuscriptRecordId}/peer-reviewers`
    const response = await http.post<any, R>(
      url,
      { author_id: authorId },
    )
    return response.data
  }

  /**
   * Delete a manuscript peer reviewer
   * @param manuscriptPeerReviewer
   */
  public static async delete(manuscriptPeerReviewer: ManuscriptPeerReviewer) {
    const { manuscript_record_id, id } = manuscriptPeerReviewer
    const response = await http.delete(
      `api/manuscript-records/${manuscript_record_id}/peer-reviewers/${id}`,
    )
    return response.status === 204
  }
}
