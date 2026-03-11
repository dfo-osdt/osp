import type { Resource, ResourceList } from '../Resource'
import type { Locale } from '@/stores/LocaleStore'
import { http } from '@/api/http'
import { SpatieQuery } from '@/api/SpatieQuery'

export interface Expertise {
  id: string // ulid
  name_en: string
  name_fr: string
  is_validated?: boolean
}

export interface ExpertiseSimilarMatch {
  expertise: Resource<Expertise>
  score: number
  matched_on: string
}

export type ExpertiseResource = Resource<Expertise>
export type ExpertiseResourceList = ResourceList<Expertise>

type R = ExpertiseResource
type RList = ExpertiseResourceList

export class ExpertiseService {
  public static async list(query?: ExpertiseQuery) {
    let url = 'api/expertises'
    if (query) {
      url += `?${query.toQueryString()}`
    }
    const response = await http.get<RList>(url)
    return response.data
  }

  public static async get(id: string) {
    const response = await http.get<R>(`api/expertises/${id}`)
    return response.data
  }

  public static async create(data: { name_en: string, name_fr: string }) {
    const response = await http.post<typeof data, R>('api/expertises', data)
    return response.data
  }

  public static async similar(nameEn: string, nameFr: string) {
    const params = new URLSearchParams()
    if (nameEn) {
      params.set('name_en', nameEn)
    }
    if (nameFr) {
      params.set('name_fr', nameFr)
    }
    const response = await http.get<{ data: ExpertiseSimilarMatch[] }>(
      `api/expertises/similar?${params.toString()}`,
    )
    return response.data
  }
}

export class ExpertiseQuery extends SpatieQuery {
  public filterNameEn(name: string) {
    return this.filter('name_en', name)
  }

  public filterNameFr(name: string) {
    return this.filter('name_fr', name)
  }

  public search(search: string) {
    return this.filter('search', search)
  }

  public sortByNameLength(locale: Locale = 'en') {
    return this.sort(`name-${locale}-length`, 'asc')
  }
}
