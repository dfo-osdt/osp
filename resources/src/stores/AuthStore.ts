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
}
class AuthenticatedUser implements IAuthenticatedUser {

    public id!: number;
    public first_name!: string;
    public last_name!: string;
    public email!: string;
    public author!: AuthorResource;

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
    const user: Ref<AuthenticatedUser | null> = useStorage('user', null);
    const loading = ref(true);
    isLoggedIn(); // check with backend - is our session still valid?

    // computed
    const isAuthenticated = computed(() => user.value !== null);

    /**
     * Internal function to check if user is still logged in by
     * interrogating user api point. Used to hydrate this
     * store
     *
     * @returns void
     */
    async function isLoggedIn() {
        loading.value = true;
        return await getUser()
            .then((resp) => {
                user.value = new AuthenticatedUser(
                    resp.data.data
                );
                return true;
            })
            .catch((err) => {
                console.log(err);
                user.value = null;
                return false;
            });
        loading.value = false;
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
                await isLoggedIn();
            })
            .catch((err) => {
                user.value = null;
                console.log(err);
                return Promise.reject(err);
            });

        loading.value = false;
    }

    /**
     * Logout user
     */
    async function logout() {
        loading.value = true;

        await sanctumLogout()
            .then(() => {
                user.value = null;
                Router.push({ name: 'login' });
                const msg = t('auth.logged-out-successfully');

                setTimeout(() => {
                    Notify.create({
                        message: msg,
                        color: 'green',
                    });
                }, 500);
            })
            .catch((err) => console.log(err));

        loading.value = false;
    }

    return {
        user,
        isAuthenticated,
        login,
        logout,
        loading,
        idleTimerMin,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
