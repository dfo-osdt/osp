import type { Resource, ResourceList } from '../Resource'
import type { UserResource } from '../User/User'
import { http } from '@/api/http'

export interface NotificationGroupMember {
  readonly id: number
  readonly created_at: string
  readonly updated_at: string
  user_id: number
  member_user_id: number
  expires_at: string | null
  user?: UserResource
  member?: UserResource
}

export interface CreateNotificationGroupMemberRequest {
  member_user_id: number
  expires_at?: string | null
}

export type NotificationGroupMemberResource
  = Resource<NotificationGroupMember>
export type NotificationGroupMemberResourceList
  = ResourceList<NotificationGroupMember>

type R = NotificationGroupMemberResource
type RList = NotificationGroupMemberResourceList

export class NotificationGroupMemberService {
  public static async list() {
    const response = await http.get<RList>(
      'api/user/notification-group-members',
    )
    return response.data
  }

  public static async create(data: CreateNotificationGroupMemberRequest) {
    const response = await http.post<CreateNotificationGroupMemberRequest, R>(
      'api/user/notification-group-members',
      data,
    )
    return response.data
  }

  public static async destroy(id: number) {
    const response = await http.delete<R>(
      `api/user/notification-group-members/${id}`,
    )
    return response.data
  }
}

export class NotificationGroupMembershipService {
  public static async list() {
    const response = await http.get<RList>(
      'api/user/notification-group-memberships',
    )
    return response.data
  }

  public static async destroy(id: number) {
    const response = await http.delete<R>(
      `api/user/notification-group-memberships/${id}`,
    )
    return response.data
  }
}
