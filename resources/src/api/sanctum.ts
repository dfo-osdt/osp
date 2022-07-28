import { http } from './http';

export const useSanctum = () => {
    const csrf = () => http.get('/sanctum/csrf-cookie');

    const user = async () => {
        await csrf();
        return http.get('/api/user');
    };

    const login = async (email: string, password: string) => {
        await csrf();
        return http.post('/login', { email, password });
    };

    const logout = async () => {
        return http.post('/logout');
    };

    return {
        user,
        login,
        logout,
    };
};
