import type { JournalResource } from '../Journal/Journal'
import type { ManuscriptRecordResource } from '../ManuscriptRecord/ManuscriptRecord'
import type { MediaResource, MediaResourceList } from '../Media/Media'
import type { SupplementaryFileType } from '../Media/supplementaryFileOptions'
import type { PublicationAuthorResource } from '../PublicationAuthor/PublicationAuthor'
import type { Resource, ResourceList } from '../Resource'
import type { UserResource } from '../User/User'
import { http } from '@/api/http'
import { SpatieQuery } from '@/api/SpatieQuery'

export type PublicationStatus = 'accepted' | 'published'

export interface Publication {
  readonly id: number
  readonly created_at: string
  readonly updated_at: string
  status: PublicationStatus
  title: string
  doi: string | null
  is_open_access: boolean
  accepted_on: string | null
  published_on: string | null
  embargoed_until: string | null
  journal_id: number
  manuscript_record_id: number
  user_id: number
  // relationships
  journal?: JournalResource
  manuscript_record?: ManuscriptRecordResource
  user?: UserResource
  publication_authors?: PublicationAuthorResource[]
}

export type PublicationCreate = Omit<
  Publication,
  | 'id'
  | 'created_at'
  | 'updated_at'
  | 'manuscript_record_id'
  | 'user_id'
  | 'publication_pdf'
>

export type PublicationResource = Resource<Publication>
export type PublicationResourceList = ResourceList<Publication>

export type R = PublicationResource
export type RList = PublicationResourceList

/**
 * Publication Service
 */
export class PublicationService {
  /** Get a list of publications */
  public static async list(query?: PublicationQuery) {
    let url = 'api/publications'
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<RList>(url)
    return response.data
  }

  /** Find a publication by id */
  public static async find(id: number) {
    const response = await http.get<R>(`api/publications/${id}`)
    return response.data
  }

  /** Create a new publication */
  public static async create(data: PublicationCreate) {
    const response = await http.post<PublicationCreate, R>(
      `api/publications`,
      data,
    )
    return response.data
  }

  /** Update a publication */
  public static async update(id: number, data: Publication) {
    const response = await http.put<Publication, R>(
      `api/publications/${id}`,
      data,
    )
    return response.data
  }

  /** Delete a publication */
  public static async delete(id: number) {
    const response = await http.delete(`api/publications/${id}`)
    return response.status === 204
  }

  /** Get a list of publications for logged in user */
  public static async getMyPublications(query?: PublicationQuery) {
    let url = 'api/my/publications'
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<RList>(url)
    return response.data
  }

  /** Get PDF media resource - if it exits */
  public static async listPDF(id: number) {
    const response = await http.get<MediaResourceList>(
      `api/publications/${id}/files`,
    )
    return response.data
  }

  /** Attach a PDF file to the publication */
  public static async attachPDF(file: File, id: number) {
    const formData = new FormData()
    formData.append('pdf', file)
    const response = await http.post<FormData, MediaResource>(
      `api/publications/${id}/files`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    )
    return response.data
  }

  /** Delete the PDF file from the publication */
  public static async deletePDF(id: number, uuid: string) {
    const response = await http.delete(`api/publications/${id}/files/${uuid}`)
    return response.status === 204
  }

  public static async listSupplementaryFiles(id: number) {
    const response = await http.get<MediaResourceList>(
      `api/publications/${id}/supplementary-files`,
    )
    return response.data
  }

  public static async attachSupplementaryFile(file: File, id: number, type: SupplementaryFileType, desc: string | null = null) {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('supplementary_file_type', type)
    if (desc) {
      formData.append('description', desc)
    }
    const response = await http.post<FormData, MediaResource>(
      `api/publications/${id}/supplementary-files`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    )
    return response.data
  }

  public static async deleteSupplementaryFile(id: number, uuid: string) {
    const response = await http.delete(`api/publications/${id}/supplementary-files/${uuid}`)
    return response.status === 204
  }
}

export class PublicationQuery extends SpatieQuery {
  public filterId(id: number[]) {
    this.filter('id', id)
    return this
  }

  public filterUserId(userId: number[]) {
    this.filter('user_id', userId)
    return this
  }

  public filterStatus(status: PublicationStatus[]) {
    this.filter('status', status)
    return this
  }

  public filterJournalID(journalId: number[]) {
    this.filter('journal_id', journalId)
    return this
  }

  public filterAuthorID(authorId: number[]) {
    this.filter('publicationAuthors.author_id', authorId)
    return this
  }

  public filterTitle(title: string) {
    this.filter('title', title)
    return this
  }

  public filterOpenAccess() {
    this.filter('open_access', true)
    return this
  }

  public filterNotUnderEmbargo() {
    this.filter('not_under_embargo', true)
    return this
  }

  public filterUnderEmbargo() {
    this.filter('under_embargo', true)
    return this
  }

  public filterSecondaryPublication() {
    this.filter('secondary_publication', true)
    return this
  }

  public sort(sort: PublicationQuerySort, direction: 'asc' | 'desc') {
    super.sort(sort, direction)
    return this
  }
}

type PublicationQuerySort = 'title' | 'created_at' | 'updated_at'
