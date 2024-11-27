import type { Resource, ResourceList, SensitivityLabel } from '../Resource'
import type { SupplementaryFileType } from './supplementaryFileOptions'

export interface Media {
  uuid: string
  file_name: string
  size_bytes: number
  created_at: string
  collection_name: string
  mime_type: string
  locked: boolean
  sensitivity_label: SensitivityLabel
  supplementary_file_type?: SupplementaryFileType
  description?: string
}

export type MediaResource = Resource<Media>
export type MediaResourceList = ResourceList<Media>
