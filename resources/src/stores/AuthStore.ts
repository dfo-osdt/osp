import { useSanctum } from '@/api/sanctum';
import { Router } from '@/plugins/router';
import { i18n } from '@/plugins/i18n';
import type { SanctumUser } from '@/api/sanctum';
import type { Ref } from 'vue';
import { Notify } from 'quasar';

class User {
    id: number;
    email: string;
    first_name: string;
    last_name: string;

    constructor(
        id: number,
        email: string,
        first_name: string,
        last_name: string
    ) {
        this.id = id;
        this.email = email;
        this.first_name = first_name;
        this.last_name = last_name;
    }

    get fullName(): string {
        return `${this.first_name} ${this.last_name}`;
    }

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
    const { t } = i18n.global;

    // initial state
    const user: Ref<User | null> = useStorage('user', null);
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
                user.value = new User(
                    resp.data.id,
                    resp.data.email,
                    resp.data.first_name,
                    resp.data.last_name
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
    };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
