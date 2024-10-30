import type { AuthorResource } from '../Author/Author'
import type { OrganizationResource } from '../Organization/Organization'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface AuthorEmployment {
  id: number
  author_id: number
  organization_id: number
  role_title: string | null
  department_name: string | null
  start_date: string
  end_date: string | null
  readonly orcid_synced: boolean
  readonly orcid_needs_sync: boolean
  readonly orcid_last_synced_at: string | null
  readonly organization?: OrganizationResource
  readonly author?: AuthorResource
}

type AuthorEmploymentUpdate = Pick<AuthorEmployment, 'id' | 'author_id' | 'department_name' | 'role_title' | 'start_date' | 'end_date' | 'organization_id'>
type AuthorEmploymentCreate = Omit<AuthorEmploymentUpdate, 'id'>

export type AuthorEmploymentResource = Resource<AuthorEmployment>
export type AuthorEmploymentResourceList = ResourceList<AuthorEmployment>

type R = AuthorEmploymentResource
type RList = AuthorEmploymentResourceList

export class AuthorEmploymentService {
  authorId: number

  constructor(authorId: number) {
    this.authorId = authorId
  }

  public async list(): Promise<RList> {
    const response = await http.get<RList>(`api/authors/${this.authorId}/employments`)
    return response.data
  }

  public async get(id: number): Promise<R> {
    const response = await http.get<R>(`api/authors/${this.authorId}/employments/${id}`)
    return response.data
  }

  public async create(data: AuthorEmploymentCreate): Promise<R> {
    const response = await http.post<AuthorEmploymentCreate, R>(
      `api/author/${this.authorId}/employments`,
      data,
    )
    return response.data
  }

  public async update(data: AuthorEmploymentUpdate): Promise<R> {
    const response = await http.put<AuthorEmploymentUpdate, R>(
      `api/authors/${this.authorId}/employments/${data.id}`,
      data,
    )
    return response.data
  }

  public async delete(data: AuthorEmployment): Promise<boolean> {
    const response = await http.delete(`api/authors/${this.authorId}/employments/${data.id}`)
    return response.status === 204
  }
}
