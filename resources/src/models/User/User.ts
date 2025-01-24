import type { Locale } from '@/stores/LocaleStore'
import type { AuthorResource } from '../Author/Author'
import type { Resource, ResourceList, SensitivityLabel } from '../Resource'
import { http } from '@/api/http'

export interface User {
  readonly id: number
  first_name: string
  last_name: string
  email: string
  locale: Locale
  sensitivity_label: SensitivityLabel
  author?: AuthorResource
}

type InviteUserRequest = Omit<User, 'id' | 'author' | 'sensitivity_label'>

export type UserResource = Resource<User>
export type UserResourceList = ResourceList<UserResource>

// User Service
export class UserService {
  /**
   * Get a list of users
   */
  public static async list(query?: string) {
    let url = 'api/users'
    if (query) {
      url += `?${query}`
    }
    const response = await http.get<UserResourceList>(url)
    return response.data
  }

  public static async get(id: number) {
    const response = await http.get<UserResource>(`api/users/${id}`)
    return response.data
  }

  public static async update(
    id: number,
    firstName: string,
    lastName: string,
    locale: Locale,
  ) {
    const response = await http.put<any, UserResource>(`api/users/${id}`, {
      first_name: firstName,
      last_name: lastName,
      locale,
    })
    return response.data
  }

  public static async invite(user: InviteUserRequest) {
    const response = await http.post<InviteUserRequest, UserResource>(
      'api/users/invite',
      user,
    )
    return response.data
  }
}
