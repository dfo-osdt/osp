interface ModelPermissions {
  update?: boolean
  delete?: boolean
  download?: boolean
}

export interface Meta {
  current_page: number
  from: number
  last_page: number
  links: MetaLink[]
  path: string
  per_page: number
  to: number
  total: number
}
interface MetaLink {
  url: string
  label: string
  active: boolean
}

interface Links {
  first: string
  last: string
  prev: string | null
  next: string | null
}

export interface Resource<T> {
  data: T
  can?: Readonly<ModelPermissions>
}

export interface ResourceList<T> {
  data: Resource<T>[]
  meta?: Readonly<Meta>
  links?: Readonly<Links>
}

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

export type SensitivityLabel = 'Unclassified' | 'Protected A'
export type SupplementaryFileType =
  | 'manuscript_record_form'
  | 'author_agreement'
  | 'joint_copyright_agreement'
  | 'preprint'
  | 'authors_accepted_manuscript'
  | 'errata'
  | 'other'

export type MediaResource = Resource<Media>
export type MediaResourceList = Resource<MediaResource[]>
