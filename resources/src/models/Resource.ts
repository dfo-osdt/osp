interface ModelPermissions {
  view?: boolean
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

export type SensitivityLabel = 'Unclassified' | 'Protected A'
