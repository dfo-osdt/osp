import type { UserResource } from './User'
import type { AuthorResource } from '@/models/Author/Author'
import type {
  Resource,
  ResourceList,
  SensitivityLabel,
} from '@/models/Resource'
import type { Locale } from '@/stores/LocaleStore'
import { http } from '@/api/http'

export type AuthenticatedUserResource = Resource<IAuthenticatedUser>
export type UserAuthenticationResource = Resource<UserAuthenticationRecord[]>

export interface UserAuthenticationRecord {
  ip_address: string
  user_agent: string
  user_agent_for_humans: string
  login_at: null | string
  login_successful: boolean
  logout_at: null | string
  location: LocationRecord | null
}

export interface LocationRecord {
  ip: string
  lat: number
  lon: number
  city: string
  state: string
  cached?: boolean
  country: string
  default: boolean
  currency: string
  iso_code: string
  timezone: string
  continent: string
  state_name: string
  postal_code: string
}

export interface UserInvitation {
  id: number
  user_id: number
  invited_by: number
  registered_at: string | null
  user: UserResource
  invited_by_user?: UserResource
  invited_at: string
}

export type UserInvitationResource = Resource<UserInvitation>
export type UserInvitationResourceList = ResourceList<UserInvitation>

export interface IAuthenticatedUser {
  id: number
  first_name: string
  last_name: string
  email: string
  locale: Locale
  sensitivity_label: SensitivityLabel
  new_password_required: boolean
  author: AuthorResource
  roles: AuthenticatedUserRoles[]
  last_login_at: null | string
  permissions: AuthenticatedUserPermissions[]
}

/**
 * List of available permissions
 */
export type AuthenticatedUserPermissions
  = | 'create_manuscript_records'
    | 'create_publications'
    | 'create_authors'
    | 'update_authors'
    | 'synchronize_author_affiliations'
    | 'create_organizations'
    | 'create_author_employments'
    | 'complete_interntal_management_review'
    | 'view_any_users'
    | 'view_any_manuscript_record'
    | 'publish_internal_reports'
    | 'update_publications'
    | 'delete_publications'
  // Regional MRF permissions - view access
    | 'can_view_nfl_mrfs'
    | 'can_view_mar_mrfs'
    | 'can_view_glf_mrfs'
    | 'can_view_que_mrfs'
    | 'can_view_onp_mrfs'
    | 'can_view_arc_mrfs'
    | 'can_view_pac_mrfs'
    | 'can_view_ncr_mrfs'
  // Regional MRF permissions - edit access
    | 'can_edit_nfl_mrfs'
    | 'can_edit_mar_mrfs'
    | 'can_edit_glf_mrfs'
    | 'can_edit_que_mrfs'
    | 'can_edit_onp_mrfs'
    | 'can_edit_arc_mrfs'
    | 'can_edit_pac_mrfs'
    | 'can_edit_ncr_mrfs'

/**
 * List of available roles
 */
export type AuthenticatedUserRoles
  = | 'author'
    | 'director'
    | 'admin'
    | 'editor'
    | 'chief_editor'
  // Regional editor roles
    | 'nfl_editor'
    | 'mar_editor'
    | 'glf_editor'
    | 'que_editor'
    | 'onp_editor'
    | 'arc_editor'
    | 'pac_editor'
    | 'ncr_editor'

export class AuthenticatedUser implements IAuthenticatedUser {
  public id!: number
  public first_name!: string
  public last_name!: string
  public email!: string
  public locale!: Locale
  public new_password_required!: boolean
  public author!: AuthorResource
  public roles!: AuthenticatedUserRoles[]
  public permissions!: AuthenticatedUserPermissions[]
  public sensitivity_label!: SensitivityLabel
  public last_login_at!: string | null

  constructor(user: IAuthenticatedUser) {
    Object.assign(this, user)
  }

  /**
   * Get the full name of the user.
   */
  get fullName(): string {
    return `${this.first_name} ${this.last_name}`
  }

  /**
   * Get the initials of the user.
   */
  get initials(): string {
    return `${this.first_name.charAt(0)}${this.last_name.charAt(0)}`
  }

  /**
   * Get the user's author id.
   */
  get authorId(): number {
    return this.author.data.id
  }

  /**
   * Get the user's last login date
   */
  get lastLoginAt(): Date | null {
    if (this.last_login_at === null) {
      return null
    }

    const date = new Date(this.last_login_at)
    return date
  }

  /**
   * Does this user have the given permission?
   *
   * @param permission
   */
  can(permission: AuthenticatedUserPermissions): boolean {
    return this.permissions.includes(permission)
  }

  /**
   * Does this user have the given role?
   *
   * @param role
   */
  hasRole(role: AuthenticatedUserRoles): boolean {
    return this.roles.includes(role)
  }

  /**
   * Can this user view regional manuscripts?
   * Users with view_any_manuscript_record or specific regional permissions can access
   */
  canViewRegionalManuscripts(): boolean {
    // Users with view_any_manuscript_record can access regional view
    if (this.can('view_any_manuscript_record')) {
      return true
    }

    // Or users with specific regional permissions
    const regionalViewPermissions: AuthenticatedUserPermissions[] = [
      'can_view_nfl_mrfs',
      'can_view_mar_mrfs',
      'can_view_glf_mrfs',
      'can_view_que_mrfs',
      'can_view_onp_mrfs',
      'can_view_arc_mrfs',
      'can_view_pac_mrfs',
      'can_view_ncr_mrfs',
    ]

    return regionalViewPermissions.some(permission => this.can(permission))
  }

  getRoleNameForDisplay(
    role: AuthenticatedUserRoles,
    locale: Locale = 'en',
  ): string {
    switch (role) {
      case 'author':
        return locale === 'en' ? 'Author' : 'Auteur'
      case 'director':
        return locale === 'en' ? 'Director' : 'Directeur'
      case 'admin':
        return locale === 'en' ? 'Admin' : 'Administrateur'
      case 'chief_editor':
        return locale === 'en' ? 'Chief Editor' : 'Rédacteur en chef'
      case 'editor':
        return locale === 'en' ? 'Editor' : 'Éditeur'
      // Regional editor roles
      case 'nfl_editor':
        return locale === 'en'
          ? 'Newfoundland and Labrador Editor'
          : 'Éditeur Terre-Neuve-et-Labrador'
      case 'mar_editor':
        return locale === 'en' ? 'Maritimes Editor' : 'Éditeur des Maritimes'
      case 'glf_editor':
        return locale === 'en' ? 'Gulf Editor' : 'Éditeur du Golfe'
      case 'que_editor':
        return locale === 'en' ? 'Quebec Editor' : 'Éditeur du Québec'
      case 'onp_editor':
        return locale === 'en'
          ? 'Ontario and Prairie Editor'
          : 'Éditeur de l\'Ontario et des Prairies'
      case 'arc_editor':
        return locale === 'en' ? 'Arctic Editor' : 'Éditeur de l\'Arctique'
      case 'pac_editor':
        return locale === 'en' ? 'Pacific Editor' : 'Éditeur du Pacifique'
      case 'ncr_editor':
        return locale === 'en'
          ? 'National Capital Region Editor'
          : 'Éditeur de la Région de la capitale nationale'
      default:
        return 'N/A'
    }
  }
}

// Authenticated User Service

export class AuthenticatedUserService {
  public static async getUser() {
    const response = await http.get<AuthenticatedUserResource>('/api/user')
    return response.data
  }

  public static async getUserAuthentications() {
    const response = await http.get<UserAuthenticationResource>(
      '/api/user/authentications',
    )
    return response.data
  }

  public static async getSentInvitations() {
    const response = await http.get<UserInvitationResourceList>(
      '/api/user/invitations',
    )
    return response.data
  }

  public static async revokeOrcid(): Promise<boolean> {
    const response = await http.post('/api/user/orcid/revoke')
    return response.status === 200
  }
}
