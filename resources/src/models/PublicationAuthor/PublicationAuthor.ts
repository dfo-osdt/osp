import type { AuthorResource } from '../Author/Author'
import type { OrganizationResource } from '../Organization/Organization'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface PublicationAuthor {
  readonly id: number
  publication_id: number
  author_id: number
  organization_id: number
  is_corresponding_author: boolean
  organization?: OrganizationResource
  author?: AuthorResource
}

export type PublicationAuthorResource = Resource<PublicationAuthor>
export type PublicationAuthorResourceList = ResourceList<PublicationAuthor>

type R = PublicationAuthorResource
type RList = PublicationAuthorResourceList

/**
 * Publication Author Service
 */
export class PublicationAuthorService {
  /**
   * Get a list of publication authors
   * @param publicationId
   */
  public static async list(publicationId: number) {
    const response = await http.get<RList>(
      `api/publications/${publicationId}/publication-authors`,
    )
    return response.data
  }

  /**
   * Delete a publication author
   * @param publicationAuthor
   */
  public static async delete(publicationAuthor: PublicationAuthor) {
    const { publication_id, id } = publicationAuthor
    const response = await http.delete(
      `api/publications/${publication_id}/publication-authors/${id}`,
    )
    return response.status === 204
  }

  /**
   * Add a publication author
   * @param publicationId - publication to add this author to
   * @param authorId - author to associate with this publication
   * @param isCorrespondingAuthor - is this author the corresponding author
   */
  public static async create(
    publicationId: number,
    authorId: number,
    isCorrespondingAuthor: boolean,
  ) {
    const url = `api/publications/${publicationId}/publication-authors`
    const response = await http.post<any, R>(url, {
      author_id: authorId,
      is_corresponding_author: isCorrespondingAuthor,
    })
    return response.data
  }

  /**
   * Update a publication author
   * @param publicationAuthor
   */
  public static async update(publicationAuthor: PublicationAuthor) {
    const { publication_id, id } = publicationAuthor
    const url = `api/publications/${publication_id}/publication-authors/${id}`
    const response = await http.put<any, R>(url, publicationAuthor)
    return response.data
  }
}
