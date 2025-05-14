import type { ManuscriptAuthorResource } from '../ManuscriptAuthor/ManuscriptAuthor'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'
import { SpatieQuery } from '@/api/SpatieQuery'

export interface Preprint {
  manuscript_record_id: number
  title: string
  region_id: number
  preprint_url: string
  accepted_on: string
  authors: ManuscriptAuthorResource[]
}

export type PreprintResource = Resource<Preprint>
export type PreprintResourceList = ResourceList<Preprint>

type PList = PreprintResourceList

type PreprintQuerySort = 'accepted_on' | 'title'

export class PreprintQuery extends SpatieQuery {
  public filterId(id: number[]) {
    this.filter('id', id)
    return this
  }

  public filterUserId(id: number[]) {
    this.filter('user_id', id)
    return this
  }

  public filterAuthorID(authorId: number[]) {
    this.filter('manuscriptAuthors.author_id', authorId)
    return this
  }

  public filterTitle(title: string) {
    this.filter('title', title)
    return this
  }

  public sort(sort: PreprintQuerySort, direciton: 'asc' | 'desc') {
    super.sort(sort, direciton)
    return this
  }
}

export class PreprintService {
  /** Get a list of the preprints - accepted and published */
  public static async list(query?: PreprintQuery) {
    let url = 'api/preprints'
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<PList>(url)
    return response.data
  }
}
