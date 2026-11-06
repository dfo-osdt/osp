import type { JournalResource } from '../Journal/Journal'
import type { Resource } from '../Resource'
import type { ReviewContributorRole } from './reviewContributorRoleOptions'
import { http } from '@/api/http'

/**
 * Where a review contribution stands with the author's ORCID record
 */
export type OrcidReviewStatus = 'not_on_orcid' | 'pending' | 'on_orcid'

/**
 * A review contribution, as listed to its author for ORCID review credit
 */
export interface OrcidReviewContribution {
  readonly id: number
  manuscript_record_id: number
  role: ReviewContributorRole
  review_date: string | null
  orcid_status: OrcidReviewStatus
  publication: {
    id: number
    title: string
    doi: string
    journal: JournalResource
  }
}

export type OrcidReviewContributionResource = Resource<OrcidReviewContribution>

export interface OrcidReviewContributionResourceList {
  data: OrcidReviewContributionResource[]
  meta: {
    can_write_to_orcid: boolean
  }
}

/**
 * The authenticated user's review contributions that can be credited on ORCID
 */
export class OrcidReviewContributionService {
  public static async list() {
    const response = await http.get<OrcidReviewContributionResourceList>(
      'api/user/orcid/review-contributions',
    )
    return response.data
  }

  /**
   * Add the contribution to the author's ORCID record, now or once they connect ORCID
   */
  public static async add(id: number) {
    const response = await http.post<null, OrcidReviewContributionResource>(
      `api/user/orcid/review-contributions/${id}/add`,
    )
    return response.data
  }
}
