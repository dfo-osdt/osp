/**
 * This utility class is used to build a query string for endpoints that
 * use the Spatie Query Builder package.
 */
export class SpatieQuery {
  filters: SpatieFilter[]
  sorts: SpatieSort[]
  includes: SpatieInclude[]
  customs: Custom[]
  trashed?: SpatieTrashedFilter
  pagination?: Pagination

  /**
   * Create a new SpatieQuery instance.
   */
  constructor() {
    this.filters = []
    this.sorts = []
    this.includes = []
    this.customs = []
  }

  /** Add a filter to the query */
  public filter(
    name: string,
    values?: boolean | string | string[] | number | number[],
  ): this {
    let val: string[] = []

    // applies the filter to the query
    if (!values) {
      val = ['1']
    }

    // uses boolean value to filter
    if (typeof values === 'boolean') {
      val = values ? ['1'] : ['0']
    }

    if (typeof values === 'string') {
      val = [values]
    }

    if (typeof values === 'number') {
      val = [values.toString()]
    }

    if (Array.isArray(values)) {
      val = values.map(v => v.toString())
    }

    this.filters.push({ name, values: val })
    return this
  }

  /** Add a sort to the query */
  public sort(name: string, direction: 'asc' | 'desc'): this {
    this.sorts.push({ name, direction })
    return this
  }

  /** Add an include to the query */
  public include(name: string): this {
    this.includes.push({ name })
    return this
  }

  /** Add a custom query parameter to the query */
  public custom(name: string, value: string): this {
    this.customs.push({ name, value })
    return this
  }

  /**
   * Add conditional when function that allows other queries to be called
   * based on a condition
   */
  public when(
    condition: boolean,
    callback: (query: this) => this,
    fallback?: (query: this) => this,
  ): this {
    if (condition) {
      return callback(this)
    }

    if (fallback) {
      return fallback(this)
    }

    return this
  }

  /** Add a pagination to the query */
  public paginate(page?: number, limit?: number): this {
    this.pagination = { page, limit }
    return this
  }

  public toQueryString(): string {
    const params = new URLSearchParams()

    if (this.filters.length) {
      this.filters.forEach((filter) => {
        params.append(
          `filter[${filter.name}]`,
          filter.values.join(','),
        )
      })
    }

    if (this.sorts.length) {
      params.append(
        'sort',
        this.sorts
          .map(s => (s.direction === 'asc' ? s.name : `-${s.name}`))
          .join(','),
      )
    }

    if (this.includes.length) {
      params.append(
        'include',
        this.includes.map(i => i.name).join(','),
      )
    }

    if (this.pagination) {
      if (this.pagination.page) {
        params.append('page', this.pagination.page.toString())
      }
      if (this.pagination.limit) {
        params.append('limit', this.pagination.limit.toString())
      }
    }

    if (this.trashed) {
      params.append('trashed', this.trashed.value)
    }

    if (this.customs) {
      this.customs.forEach((custom) => {
        params.append(custom.name, custom.value)
      })
    }

    return params.toString()
  }
}
interface SpatieFilter {
  name: string
  values: string[]
}

interface SpatieSort {
  name: string
  direction: 'asc' | 'desc'
}

interface SpatieInclude {
  name: string
}

interface Pagination {
  limit?: number
  page?: number
}

interface Custom {
  name: string
  value: string
}

/**
 *   The FiltersTrashed filter responds to particular values:
 *   with: include soft-deleted records to the result set
 *   only: return only 'trashed' records at the result set
 *   any other value: return only records without that are not soft-deleted in the result set
 */
export interface SpatieTrashedFilter {
  name: 'trashed'
  value: 'with' | 'only' | 'without'
}
