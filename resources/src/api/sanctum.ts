import { AuthenticatedUserResource } from '@/stores/AuthStore';
import { http } from './http';

export interface SanctumUser {
    email: string;
    password: string;
    remember?: boolean;
    locale?: string;
}
export interface SanctumRegisterUser extends SanctumUser {
    first_name: string;
    last_name: string;
    password_confirmation: string;
}

export const useSanctum = () => {
    const csrf = () => http.get('/sanctum/csrf-cookie');

    const getUser = async () => {
        await csrf();
        return http.get<AuthenticatedUserResource>('/api/user');
    };

    const login = async (user: SanctumUser) => {
        await csrf();
        return await http.post('/login', user);
    };

    const logout = async () => {
        return await http.post('/logout');
    };

    const register = async (user: SanctumRegisterUser) => {
        await csrf();
        return await http.post<SanctumRegisterUser, any>('/register', user);
    };

    return {
        getUser,
        login,
        logout,
        register,
    };
};
