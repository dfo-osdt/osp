import type { OrganizationResource } from '../Organization/Organization'
import type { Resource, ResourceList } from '../Resource'
import type {
  ExpertiseResource,
  ExpertiseResourceList,
} from '../Expertise/Expertise'
import { http } from '@/api/http'

export interface Author {
  readonly id: number
  first_name: string
  last_name: string
  orcid: string
  orcid_verified: boolean
  orcid_connected: boolean
  email: string
  user_id: number | null
  organization_id: number
  // relationships
  organization?: OrganizationResource
  expertises?: ExpertiseResource[]
}

export type AuthorResource = Resource<Author>
export type AuthorResourceList = ResourceList<AuthorResource>

// Author Service
export class AuthorService {
  /**
   * Get a list of authors
   * @returns author list
   */
  public static async list(query?: string) {
    let url = 'api/authors'
    if (query) {
      url += `?${query}`
    }
    const response = await http.get<AuthorResourceList>(url)
    return response.data
  }

  /**
   * Find an author by author id.
   * @param id
   * @returns
   */
  public static async find(id: number) {
    const response = await http.get<AuthorResource>(`api/authors/${id}`)
    return response.data
  }

  /**
   * Update an author
   *
   * @param author
   */
  public static async update(author: Author) {
    const response = await http.put<Author, AuthorResource>(
            `api/authors/${author.id}`,
            author,
    )
    return response.data
  }

  /**
   * Create an author
   */
  public static async create(author: Partial<Author>) {
    const response = await http.post<Partial<Author>, AuthorResource>(
      'api/authors',
      author,
    )
    return response.data
  }

  /** verify a user orcid */
  public static async verifyOrcid(code: string) {
    const response = await http.post<{ code: string }, AuthorResource>(
      'api/user/orcid/verify',
      { code },
    )
    return response.data
  }

  public static async syncExpertises(
    authorId: number,
    expertises: ExpertiseResource[],
  ) {
    const response = await http.post<
      { expertises: string[] },
      ExpertiseResourceList
    >(`api/authors/${authorId}/expertises`, {
      expertises: expertises.map(e => e.data.id),
    })
    return response.data
  }
}
