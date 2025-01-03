import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

export interface Announcement {
  id: number
  title_en: string
  title_fr: string
  text_en: string
  text_fr: string
  start_at: string
  end_at: string
}

export type AnnouncementResource = Resource<Announcement>
export type AnnouncementResourceList = ResourceList<Announcement>

type RList = AnnouncementResourceList

export class AnnouncementService {
  public static async list() {
    const response = await http.get<RList>('api/announcements')
    if (response.status === 204) {
      return null
    }
    return response.data
  }
}
