import type { JournalResource } from '../Journal/Journal'
import type { PublicationStatus } from '../Publication/Publication'
import type { Region } from '../Region/Region'
import type { Resource, ResourceList } from '../Resource'
import { http } from '@/api/http'

/**
 * A single row of the editor "due queue": a secondary publication accepted by
 * Science Publications that is awaiting publication.
 */
export interface EditorQueuePublication {
  readonly id: number
  title: string
  catalogue_number: string | null
  status: PublicationStatus
  accepted_on: string | null
  manuscript_record_id: number | null
  in_planning_binder: boolean
  contact_name: string | null
  contact_email: string | null
  // relationships
  journal?: JournalResource
  region?: Resource<Region>
}

export interface EditorDashboardCounts {
  awaiting_scientific_publication: number
  in_scientific_publication: number
  in_planning_binder: number
}

export type EditorQueuePublicationResource = Resource<EditorQueuePublication>

export interface EditorDashboardResponse
  extends ResourceList<EditorQueuePublication> {
  counts: EditorDashboardCounts
}

export class EditorDashboardService {
  /**
   * Fetch the national editor dashboard: top-line counts and the paginated
   * due queue of accepted secondary publications.
   */
  public static async get(page = 1): Promise<EditorDashboardResponse> {
    const response = await http.get<EditorDashboardResponse>(
      '/api/editor-dashboard',
      { params: { page } },
    )
    return response.data
  }
}
