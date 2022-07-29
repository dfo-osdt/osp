import { http } from './http';

export interface SanctumUser {
    email: string;
    password: string;
    remember?: boolean;
}

export const useSanctum = () => {
    const csrf = () => http.get('/sanctum/csrf-cookie');

    const getUser = async () => {
        await csrf();
        return http.get('/api/user');
    };

    const login = async (user: SanctumUser) => {
        await csrf();
        return http.post('/login', user);
    };

    const logout = async () => {
        return http.post('/logout');
    };

    return {
        getUser,
        login,
        logout,
    };
};
