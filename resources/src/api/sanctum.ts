import { AuthenticatedUserResource } from '@/stores/AuthStore';
import { http } from './http';

export type locale = 'en' | 'fr';
export interface SanctumUser {
    email: string;
    password: string;
    remember?: boolean;
    locale?: locale;
}
export interface SanctumRegisterUser extends SanctumUser {
    first_name: string;
    last_name: string;
    password_confirmation: string;
}

export interface SanctumRegisterResponse {
    message: string;
    email: string;
}

export interface SanctumForgotPasswordRequest {
    email: string;
    locale?: locale;
}

export interface SanctumStatusResponse {
    status: string;
}

export interface SanctumResetPasswordRequest {
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
    locale?: locale;
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
        return await http.post<SanctumRegisterUser, SanctumRegisterResponse>(
            '/register',
            user
        );
    };

    const forgotPassword = async (email: string, locale: locale) => {
        await csrf();
        return await http.post<
            SanctumForgotPasswordRequest,
            SanctumStatusResponse
        >('/forgot-password', { email, locale });
    };

    const resetPassword = async (data: SanctumResetPasswordRequest) => {
        await csrf();
        return await http.post<
            SanctumResetPasswordRequest,
            SanctumStatusResponse
        >('/reset-password', data);
    };

    return {
        getUser,
        login,
        logout,
        register,
        forgotPassword,
        resetPassword,
    };
};
