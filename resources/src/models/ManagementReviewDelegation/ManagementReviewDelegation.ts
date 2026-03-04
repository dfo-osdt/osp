import type { Resource, ResourceList } from '../Resource'
import type { UserResource } from '../User/User'
import { http } from '@/api/http'

export interface ManagementReviewDelegation {
  readonly id: number
  readonly created_at: string
  readonly updated_at: string
  user_id: number
  delegate_user_id: number
  starts_at: string | null
  ends_at: string
  ended_early_at: string | null
  comment: string | null
  is_active: boolean
  is_scheduled: boolean
  user?: UserResource
  delegate?: UserResource
}

export interface CreateDelegationRequest {
  delegate_user_id: number
  starts_at?: string | null
  ends_at: string
  comment?: string | null
}

export type ManagementReviewDelegationResource
  = Resource<ManagementReviewDelegation>
export type ManagementReviewDelegationResourceList
  = ResourceList<ManagementReviewDelegation>

type R = ManagementReviewDelegationResource
type RList = ManagementReviewDelegationResourceList

export class ManagementReviewDelegationService {
  public static async list() {
    const response = await http.get<RList>(
      'api/user/management-review-delegations',
    )
    return response.data
  }

  public static async create(data: CreateDelegationRequest) {
    const response = await http.post<CreateDelegationRequest, R>(
      'api/user/management-review-delegations',
      data,
    )
    return response.data
  }

  public static async destroy(id: number) {
    const response = await http.delete<R>(
      `api/user/management-review-delegations/${id}`,
    )
    return response.data
  }
}
