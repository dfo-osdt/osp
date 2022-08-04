import { useSanctum } from '@/api/sanctum';
import { Router } from '@/plugins/router';
import type { SanctumUser } from '@/api/sanctum';
import type { Ref } from 'vue';

interface User {
    id: number;
    email: string;
    name: string;
}

export const useAuthStore = defineStore('AuthStore', () => {
    const {
        getUser,
        login: sanctumLogin,
        logout: sanctumLogout,
    } = useSanctum();

    const user: Ref<User | null> = ref(null);
    isLoggedIn(); // check with backend - is our session still valid?
    const isAuthenticated = computed(() => user.value !== null);

    /**
     * Internal function to check if user is still logged in by
     * interrogating user api point. Used to hydrate this
     * store
     *
     * @returns void
     */
    async function isLoggedIn() {
        console.log('running is logged in');
        return await getUser()
            .then((resp) => {
                user.value = resp.data;
                return true;
            })
            .catch((err) => {
                console.log(err);
                user.value = null;
                return false;
            });
    }

    async function login(email: string, password: string, remember = false) {
        //is user already authenticated?
        if (isAuthenticated.value) return false;

        const sanctumUser: SanctumUser = {
            email,
            password,
            remember,
        };

        await sanctumLogin(sanctumUser)
            .then(() => {
                isLoggedIn();
            })
            .catch((err) => {
                user.value = null;
                console.log(err);
                return Promise.reject(err);
            });
    }

    async function logout() {
        await sanctumLogout()
            .then(() => {
                user.value = null;
                Router.push('/');
            })
            .catch((err) => console.log(err));
    }

    return { user, isAuthenticated, login, logout };
});

if (import.meta.hot)
    import.meta.hot.accept(acceptHMRUpdate(useAuthStore, import.meta.hot));
