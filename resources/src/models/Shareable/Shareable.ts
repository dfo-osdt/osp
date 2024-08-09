import type { Resource, ResourceList } from '../Resource'
import type { UserResource } from '../User/User'
import { http } from '@/api/http'

export interface Shareable {
  readonly id: string // ulid
  user_id: number
  shared_by: number
  can_edit: boolean
  can_delete: boolean
  expires_at: string | null
  created_at: string
  updated_at: string
  user: UserResource
  sharingUser: UserResource
}

export interface ShareableRequest {
  user_id: number
  can_edit: boolean
  can_delete: boolean
  expires_at: string | null
}

export type ShareableResource = Resource<Shareable>
export type ShareableResourceList = ResourceList<Shareable>

export type ShareableModel = 'manuscript-records'

export class ShareableService {
  model: ShareableModel
  modelId: number | string

  constructor(model: ShareableModel, modelId: number | string) {
    this.model = model
    this.modelId = modelId
  }

  public async list() {
    const response = await http.get<ShareableResourceList>(
            `api/${this.model}/${this.modelId}/sharing`,
    )
    return response.data
  }

  public async get(id: string) {
    const response = await http.get<ShareableResource>(
            `api/${this.model}/${this.modelId}/sharing/${id}`,
    )
    return response.data
  }

  public async create(shareable: ShareableRequest) {
    const response = await http.post<ShareableRequest, ShareableResource>(
            `api/${this.model}/${this.modelId}/sharing`,
            shareable,
    )
    return response.data
  }

  public async update(
    shareable: Pick<
      Shareable,
            'id' | 'can_edit' | 'can_delete' | 'expires_at'
    >,
  ) {
    const request = {
      can_edit: shareable.can_edit,
      can_delete: shareable.can_delete,
      expires_at: shareable.expires_at,
    }

    const response = await http.put<typeof request, ShareableResource>(
            `api/${this.model}/${this.modelId}/sharing/${shareable.id}`,
            request,
    )
    return response.data
  }

  public async delete(id: string) {
    const response = await http.delete(
            `api/${this.model}/${this.modelId}/sharing/${id}`,
    )
    if (response.status === 204) {
      return true
    }
  }
}
