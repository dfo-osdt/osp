import { useSanctum } from '@/api/sanctum';
import { Locale } from '@/stores/LocaleStore';
import { i18n } from '@/plugins/i18n';
import type { SanctumUser } from '@/api/sanctum';
import type { Ref } from 'vue';
import { Notify } from 'quasar';
import {
    AuthenticatedUser,
    AuthenticatedUserService,
    UserAuthenticationRecord,
} from '../models/User/AuthenticatedUser';

export const useAuthStore = defineStore('AuthStore', () => {
    const { login: sanctumLogin, logout: sanctumLogout } = useSanctum();
    const localeStore = useLocaleStore();
    const idleTimerMin: number = import.meta.env.VITE_IDLE_TIMER_MIN || 30;
    const { t } = i18n.global;

    // initial state
    //const user: Ref<AuthenticatedUser | null> = useStorage('authUser', null);
    const user: Ref<AuthenticatedUser | null> = ref(null);
    const userAuthentications: Ref<UserAuthenticationRecord[] | null> =
        ref(null);
    const loading = ref(true);
    const authenticationsLoading = ref(false);
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
        try {
            user.value = await getAuthenticatedUser();
        } catch (err) {
            user.value = null;
            console.log(err);
        } finally {
            loading.value = false;
        }
    }

    /**
     * Get authenticated user from backend
     * @returns AuthenticatedUser | null (if not authenticated)
     */
    async function getAuthenticatedUser(): Promise<AuthenticatedUser | null> {
        try {
            const response = await AuthenticatedUserService.getUser();
            const authUser = new AuthenticatedUser(response.data);
            return authUser;
        } catch (err) {
            return null;
        }
    }

    async function getAuthentications(force = false): Promise<boolean> {
        if (userAuthentications.value && !force) return true;
        authenticationsLoading.value = true;
        try {
            const response =
                await AuthenticatedUserService.getUserAuthentications();
            userAuthentications.value = response.data;
            return true;
        } catch (err) {
            console.log(err);
            return false;
        } finally {
            authenticationsLoading.value = false;
        }
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
            locale: localeStore.locale as Locale,
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
    const leftDrawerOpen = useStorage('leftDrawerOpen', true);

    return {
        user,
        isAuthenticated,
        login,
        logout,
        refreshUser,
        loading,
        idleTimerMin,
        getAuthentications,
        userAuthentications,
        authenticationsLoading,
        // general application state
        isDrawerMini,
        leftDrawerOpen,
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
