import { http } from './http'

export interface SanctumUser {
  email: string
  password: string
  remember?: boolean
}
export interface SanctumRegisterUser extends SanctumUser {
  first_name: string
  last_name: string
  password_confirmation: string
  locale?: string
}

export interface SanctumRegisterResponse {
  message: string
  email: string
}

export interface SanctumForgotPasswordRequest {
  email: string
}

export interface SanctumStatusResponse {
  status: string
}

export interface SanctumResetPasswordRequest {
  email: string
  password: string
  password_confirmation: string
  token: string
}

export interface SanctumChangePasswordRequest {
  current_password: string
  password: string
  password_confirmation: string
}

export function useSanctum() {
  const csrf = () => http.get('/sanctum/csrf-cookie')

  const login = async (user: SanctumUser) => {
    await csrf()
    return await http.post('/login', user)
  }

  const register = async (user: SanctumRegisterUser) => {
    await csrf()
    return await http.post<SanctumRegisterUser, SanctumRegisterResponse>(
      '/register',
      user,
    )
  }

  const forgotPassword = async (email: string) => {
    await csrf()
    return await http.post<SanctumForgotPasswordRequest, SanctumStatusResponse>(
      '/forgot-password',
      { email },
    )
  }

  const resetPassword = async (data: SanctumResetPasswordRequest) => {
    await csrf()
    return await http.post<SanctumResetPasswordRequest, SanctumStatusResponse>(
      '/reset-password',
      data,
    )
  }

  // Methods below will only work if the user is authenticated,
  // they do not need to call csrf() first.

  const logout = async () => {
    return await http.post('/logout')
  }

  const changePassword = async (data: SanctumChangePasswordRequest) => {
    return await http.post<SanctumChangePasswordRequest, SanctumStatusResponse>(
      '/change-password',
      data,
    )
  }

  return {
    login,
    logout,
    register,
    forgotPassword,
    resetPassword,
    changePassword,
  }
}
