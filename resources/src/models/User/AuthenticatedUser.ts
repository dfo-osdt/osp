import { Locale } from '@/stores/LocaleStore';
import { AuthorResource } from '@/models/Author/Author';
import { Resource, ResourceList } from '@/models/Resource';
import { UserResource } from './User';
import { http } from '@/api/http';

export type AuthenticatedUserResource = Resource<IAuthenticatedUser>;
export type UserAuthenticationResource = Resource<UserAuthenticationRecord[]>;

export interface UserAuthenticationRecord {
    ip_address: string;
    user_agent: string;
    user_agent_for_humans: string;
    login_at: null | string;
    login_successful: boolean;
    logout_at: null | string;
    location: LocationRecord | null;
}

export interface LocationRecord {
    ip: string;
    lat: number;
    lon: number;
    city: string;
    state: string;
    cached?: boolean;
    country: string;
    default: boolean;
    currency: string;
    iso_code: string;
    timezone: string;
    continent: string;
    state_name: string;
    postal_code: string;
}

export interface UserInvitation {
    id: number;
    user_id: number;
    invited_by: number;
    registered_at: string;
    user: UserResource;
    invited_by_user?: UserResource;
}

type UserInvitationResourceList = ResourceList<UserInvitation>;

export interface IAuthenticatedUser {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    locale: Locale;
    new_password_required: boolean;
    author: AuthorResource;
    roles: string[];
    permissions: string[];
}

/**
 * List of available permissions
 */
export type AuthenticatedUserPermissions =
    | 'create_manuscript_records'
    | 'create_publications'
    | 'create_authors'
    | 'update_authors'
    | 'create_organizations'
    | 'withhold_and_complete_management_review';
/**
 * List of available roles
 */

export type AuthenticatedUserRoles = 'author' | 'director';

export class AuthenticatedUser implements IAuthenticatedUser {
    public id!: number;
    public first_name!: string;
    public last_name!: string;
    public email!: string;
    public locale!: Locale;
    public new_password_required!: boolean;
    public author!: AuthorResource;
    public roles!: string[];
    public permissions!: string[];

    constructor(user: IAuthenticatedUser) {
        Object.assign(this, user);
    }

    /**
     * Get the full name of the user.
     */
    get fullName(): string {
        return `${this.first_name} ${this.last_name}`;
    }

    /**
     * Get the initials of the user.
     */
    get initials(): string {
        return `${this.first_name.charAt(0)}${this.last_name.charAt(0)}`;
    }

    /**
     * Get the user's author id.
     */
    get authorId(): number {
        return this.author.data.id;
    }

    /**
     * Does this user have the given permission?
     *
     * @param permission
     * @returns
     */
    can(permission: AuthenticatedUserPermissions): boolean {
        return this.permissions.includes(permission);
    }

    /**
     * Does this user have the given role?
     *
     * @param role
     * @returns
     */
    hasRole(role: AuthenticatedUserRoles): boolean {
        return this.roles.includes(role);
    }
}

// Authenticated User Service

export class AuthenticatedUserService {
    public static async getUser() {
        const response = await http.get<AuthenticatedUserResource>('/api/user');
        return response.data;
    }

    public static async getUserAuthentications() {
        const response = await http.get<UserAuthenticationResource>(
            '/api/user/authentications'
        );
        return response.data;
    }

    public static async getSentInvitations() {
        const response = await http.get<UserInvitationResourceList>(
            '/api/user/invitations'
        );
        return response.data;
    }
}
