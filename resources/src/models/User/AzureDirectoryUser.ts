import type { Resource, ResourceList } from '../Resource'
import type { Locale } from '@/stores/LocaleStore'
import { http } from '@/api/http'

export interface AzureDirectoryUser {
  id: string
  display_name: string
  first_name: string
  last_name: string
  job_title: string
  email: string
  locale: Locale
}

export type AzureDirectoryUserResource = Resource<AzureDirectoryUser>
export type AzureDirectoryUserResourceList = ResourceList<AzureDirectoryUserResource>

export class AzureDirectoryUserService {
  public static async search(query: string) {
    const response = await http.post<{ search: string }, AzureDirectoryUserResourceList>(`api/azure-directory/users`, { search: query })
    return response.data
  }
}
