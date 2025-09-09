import type {
  ExpertiseResource,
  ExpertiseResourceList,
} from '../Expertise/Expertise'
import type { OrganizationResource } from '../Organization/Organization'
import type { PublicationQuery, PublicationResourceList } from '../Publication/Publication'
import type { Resource, ResourceList, SensitivityLabel } from '../Resource'
import { http } from '@/api/http'
import { SpatieQuery } from '@/api/SpatieQuery'

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
  sentivity_label: SensitivityLabel
  // relationships
  organization?: OrganizationResource
  expertises?: ExpertiseResource[]
}

export type AuthorResource = Resource<Author>
export type AuthorResourceList = ResourceList<Author>

// Author Service
export class AuthorService {
  /**
   * Get a list of authors
   * @returns author list
   */
  public static async list(query?: AuthorQuery) {
    let url = 'api/authors'
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<AuthorResourceList>(url)
    return response.data
  }

  /**
   * Find an author by author id.
   * @param id
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

  /** Get the authors published publications */
  public static async getPublications(id: number, query?: PublicationQuery) {
    let url = `api/authors/${id}/publications`
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<PublicationResourceList>(url)
    return response.data
  }
}

export class AuthorQuery extends SpatieQuery {
  public filterId(id: number[]) {
    this.filter('id', id)
    return this
  }

  public filterFirstName(firstName: string) {
    this.filter('first_name', firstName)
    return this
  }

  public filterLastName(lastName: string) {
    this.filter('last_name', lastName)
    return this
  }

  public filterEmail(email: string) {
    this.filter('email', email)
    return this
  }

  public filterOrganizationId(organizationId: number) {
    this.filter('organization_id', organizationId)
    return this
  }

  public filterOrcid(orcid: string) {
    this.filter('orcid', orcid)
    return this
  }

  public filterSearch(search: string) {
    this.filter('search', search)
    return this
  }

  public filterInternalAuthor() {
    this.filter('internal_author', true)
    return this
  }

  public filterExternalAuthor() {
    this.filter('external_author', true)
    return this
  }

  public filterWithOrcid() {
    this.filter('with_orcid', true)
    return this
  }

  public sort(sort: AuthorQuerySort, direction: 'asc' | 'desc') {
    super.sort(sort, direction)
    return this
  }

  public include(name: AuthorQueryInclude) {
    super.include(name)
    return this
  }
}

type AuthorQuerySort = 'first_name' | 'last_name' | 'email'
type AuthorQueryInclude = 'organization' | 'expertises'
