import { http } from './http';

export const useSanctum = () => {
    const csrf = () => http.get('/sanctum/csrf-cookie');

    const login = async (email: string, password: string) => {
        await csrf();

        http.post('/login', { email, password }).then();
    };

    return {
        login,
    };
};
