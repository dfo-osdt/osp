import { useSanctum } from '@/api/sanctum';
import { Router } from '@/plugins/router';
import { i18n } from '@/plugins/i18n';
import type { SanctumUser } from '@/api/sanctum';
import type { Ref } from 'vue';
import { Notify } from 'quasar';
import { AuthorResource } from '@/models/Author/Author';
import { Resource } from '@/models/Resource';

export type AuthenticatedUserResource = Resource<IAuthenticatedUser>;

export interface IAuthenticatedUser {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    author: AuthorResource;
    roles: string[];
    permissions: string[];
}
/**
 * List of available permissions
 */
type AuthenticatedUserPermissions =
    | 'create_manuscript_records'
    | 'create_publications'
    | 'create_authors'
    | 'update_authors'
    | 'create_organizations'
    | 'withhold_and_complete_management_review';

/**
 * List of available roles
 */
type AuthenticatedUserRoles = 'author' | 'director';

class AuthenticatedUser implements IAuthenticatedUser {
    public id!: number;
    public first_name!: string;
    public last_name!: string;
    public email!: string;
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

export const useAuthStore = defineStore('AuthStore', () => {
    const {
        getUser,
        login: sanctumLogin,
        logout: sanctumLogout,
    } = useSanctum();
    const localeStore = useLocaleStore();
    const idleTimerMin: number = import.meta.env.VITE_IDLE_TIMER_MIN || 30;
    const { t } = i18n.global;

    // initial state
    // const user: Ref<AuthenticatedUser | null> = useStorage('authUser', null);
    const user: Ref<AuthenticatedUser | null> = ref(null);
    const loading = ref(true);
    refreshUser(); // check with backend - is our session still valid?

    // computed
    const isAuthenticated = computed(() => user.value !== null);

    /**
     * Internal function to check if user is still logged in by
     * interrogating user api point. Used to hydrate this
     * store
     *
     * @returns void
     */
    async function refreshUser(silent = false) {
        if (!silent) loading.value = true;
        return await getUser()
            .then((resp) => {
                user.value = new AuthenticatedUser(resp.data.data);
                loading.value = false;
                return true;
            })
            .catch((err) => {
                user.value = null;
                return false;
            });
    }

    /**
     * Login user with email and password
     *
     * @param email
     * @param password
     * @returns void
     * @throws Error if login fails
     */
    async function login(email: string, password: string, remember = false) {
        //is user already authenticated?
        loading.value = true;
        if (isAuthenticated.value) return false;

        const sanctumUser: SanctumUser = {
            email,
            password,
            remember,
            locale: localeStore.locale,
        };

        // login user and get user data
        await sanctumLogin(sanctumUser)
            .then(async () => {
                await refreshUser();
            })
            .catch((err) => {
                user.value = null;
                return Promise.reject(err);
            });

        loading.value = false;
    }

    /**
     * Logout user
     */
    async function logout() {
        loading.value = true;
        await sanctumLogout().then(() => {
            user.value = null;

            const msg = t('auth.logged-out-successfully');

            setTimeout(() => {
                Notify.create({
                    message: msg,
                    color: 'green',
                });
            }, 500);
        });
        loading.value = false;
    }

    /**
     * general application state - we can eventually move this to a separate store
     * should it grow too large
     */
    const isDrawerMini = useStorage('isDrawerMini', true);

    return {
        user,
        isAuthenticated,
        login,
        logout,
        refreshUser,
        loading,
        idleTimerMin,
        // general application state
        isDrawerMini,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
