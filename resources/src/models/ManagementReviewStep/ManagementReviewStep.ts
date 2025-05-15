import type { ManuscriptRecordSummaryResource } from '../ManuscriptRecord/ManuscriptRecord'
import type { Resource, ResourceList, SensitivityLabel } from '../Resource'
import type { UserResource } from '../User/User'
import { http } from '@/api/http'
import { SpatieQuery } from '@/api/SpatieQuery'

export type ManagementReviewStepDecision =
  | 'none'
  | 'complete'
  | 'revision'
  | 'withdrawn'
export type ManagementReviewStepStatus =
  | 'pending'
  | 'reassign'
  | 'completed'
  | 'on_hold'
export type UpdateStep = Pick<ManagementReviewStep, 'comments'>
export interface DecisionStep {
  comments?: string
  next_user_id?: number
}

export interface ManagementReviewStep {
  readonly id: number
  readonly created_at: string
  readonly updated_at: string
  readonly completed_at: string | null
  readonly decision_expected_by: string
  comments: string
  decision: ManagementReviewStepDecision
  status: ManagementReviewStepStatus
  manuscript_record_id: number
  previous_step_id: number | null
  user_id: number
  sensitivity_label: SensitivityLabel
  // relationships
  manuscript_record?: ManuscriptRecordSummaryResource
  user?: UserResource
  previous_step?: ManagementReviewStepResource
  can_complete: boolean
}

export type ManagementReviewStepResource = Resource<ManagementReviewStep>
export type ManagementReviewStepResourceList =
  ResourceList<ManagementReviewStep>

type R = ManagementReviewStepResource
type RList = ManagementReviewStepResourceList

export class ManagementReviewStepService {
  /**
   * Get all my management review steps.
   */
  public static async listMy(query?: ManagementReviewQuery) {
    let url = 'api/my/management-review-steps'
    if (query)
      url += `?${query.toQueryString()}`
    const response = await http.get<RList>(url)

    return response.data
  }

  /**
   * Get a list of management review steps from a given manuscript record.
   *
   * @param manuscriptRecordId the manuscript record to get the management review steps for
   * @returns all existing management review steps for the manuscript record
   */
  public static async list(manuscriptRecordId: number) {
    const response = await http.get<RList>(
      `api/manuscript-records/${manuscriptRecordId}/management-review-steps`,
    )
    return response.data
  }

  /**
   * Update the management review step changes. Currently only the comments
   * will be changed here.
   */
  public static async update(step: ManagementReviewStep) {
    const response = await http.put<UpdateStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}`,
      { comments: step.comments },
    )
    return response.data
  }

  /**
   * Complete the management review step
   *
   * @param step the management review step to complete
   * @param nextUserId the user id of the next user in the workflow - if null, the workflow will be completed
   */
  public static async complete(
    step: ManagementReviewStep,
  ) {
    const data: DecisionStep = {
      comments: step.comments,
    }

    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/complete`,
      data,
    )
    return response.data
  }

  /**
   * Reassign the management review step.
   *
   * @param step the management review step to reassign
   * @param nextUserId the user id of the next user in the workflow.
   */
  public static async reassign(
    step: ManagementReviewStep,
    nextUserId: number,
  ) {
    const data: DecisionStep = {
      comments: step.comments,
      next_user_id: nextUserId,
    }

    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/reassign`,
      data,
    )
    return response.data
  }

  /**
   * Refer the management review step to the next manager.
   *
   * @param step the management review step to reassign
   * @param nextUserId the user id of the next user in the workflow.
   */
  public static async refer(
    step: ManagementReviewStep,
    nextUserId: number,
  ) {
    const data: DecisionStep = {
      comments: step.comments,
      next_user_id: nextUserId,
    }

    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/refer`,
      data,
    )
    return response.data
  }

  /**
   * Flag the management review and send it back to the author.
   *
   * @param step the management review step to flag
   */
  public static async revision(step: ManagementReviewStep) {
    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/revision`,
      { comments: step.comments },
    )
    return response.data
  }

  /**
   * Response the management review flag
   */
  public static async response(step: ManagementReviewStep) {
    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/revision-response`,
      { comments: step.comments },
    )
    return response.data
  }

  /**
   * Withdraw the manuscript record from the management review. This is only possible when
   * the manuscript is on_hold.
   *
   * @param step the management review step to withdraw
   */
  public static async withdraw(step: ManagementReviewStep) {
    if (step.status !== 'on_hold')
      throw new Error('Cannot withdraw a step that is not on hold')
    const response = await http.put<DecisionStep, R>(
      `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/withdraw`,
      { comments: step.comments },
    )
    return response.data
  }
}

export class ManagementReviewQuery extends SpatieQuery {
  public filterUserId(userId: number) {
    return this.filter('user_id', userId)
  }

  public filterStatus(status: ManagementReviewStepStatus[]) {
    return this.filter('status', status)
  }

  public filterDecision(decision: ManagementReviewStepDecision[]) {
    return this.filter('decision', decision)
  }

  public filterManuscriptRecordTitle(title: string[]) {
    return this.filter('manuscriptRecord.title', title)
  }

  public includeManuscriptRecord() {
    return this.include('manuscriptRecord')
  }

  public includeUser() {
    return this.include('user')
  }

  public sort(sort: ManagementReviewQuerySort, direction: 'asc' | 'desc') {
    return super.sort(sort, direction)
  }
}

type ManagementReviewQuerySort = 'created_at' | 'updated_at'
