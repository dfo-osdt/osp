import type { Locale } from '@/stores/LocaleStore'
import { http } from './http'

export interface PlsRequest {
  abstract: string
  locale: Locale
}

export interface PlsResponse {
  data: {
    pls: string
  }
}

export class UtilityService {
  /**
   * Get a plain language summary from the given abstract text.
   */
  public static async generatePls(abstract: string, locale: Locale): Promise<PlsResponse> {
    const response = await http.post<PlsRequest, PlsResponse>(
      `api/utils/generate-pls`,
      { abstract, locale },
    )

    return response.data
  }

  /**
   * Translate a given text to the specified locale.
   * @param abstract The text to translate.
   * @param locale The target locale for translation.
   */
  public static async translatePls(abstract: string, locale: Locale): Promise<PlsResponse> {
    const response = await http.post<PlsRequest, PlsResponse>(
      `api/utils/translate-pls`,
      { abstract, locale },
    )

    return response.data
  }
}
