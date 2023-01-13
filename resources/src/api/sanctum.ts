import {
    AuthenticatedUserResource,
    UserAuthenticationResource,
} from '@/stores/AuthStore';
import { http } from './http';
import { Locale } from './Locale';

export interface SanctumUser {
    email: string;
    password: string;
    remember?: boolean;
    locale?: Locale;
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
    locale?: Locale;
}

export interface SanctumStatusResponse {
    status: string;
}

export interface SanctumResetPasswordRequest {
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
    locale?: Locale;
}

export interface SanctumChangePasswordRequest {
    current_password: string;
    password: string;
    password_confirmation: string;
    locale?: Locale;
}

export const useSanctum = () => {
    const csrf = () => http.get('/sanctum/csrf-cookie');

    const login = async (user: SanctumUser) => {
        await csrf();
        return await http.post('/login', user);
    };

    const register = async (user: SanctumRegisterUser) => {
        await csrf();
        return await http.post<SanctumRegisterUser, SanctumRegisterResponse>(
            '/register',
            user
        );
    };

    const forgotPassword = async (email: string, locale: Locale) => {
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

    // Methods below will only work if the user is authenticated,
    // they do not need to call csrf() first.
    const getUser = async () => {
        return http.get<AuthenticatedUserResource>('/api/user');
    };

    const getUserAuthentications = async () => {
        return http.get<UserAuthenticationResource>(
            '/api/user/authentications'
        );
    };

    const logout = async () => {
        return await http.post('/logout');
    };

    const changePassword = async (data: SanctumChangePasswordRequest) => {
        return await http.post<
            SanctumChangePasswordRequest,
            SanctumStatusResponse
        >('/change-password', data);
    };

    return {
        getUser,
        getUserAuthentications,
        login,
        logout,
        register,
        forgotPassword,
        resetPassword,
        changePassword,
    };
};
