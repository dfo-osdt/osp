import axios, { AxiosInstance, AxiosRequestConfig, AxiosResponse } from 'axios';
import { Notify } from 'quasar';
import { ErrorResponse, extractErrorMessages } from './errors';

enum StatusCode {
    Unauthorized = 401,
    Forbidden = 403,
    NotFound = 404,
    TooManyRequests = 429,
    ValidationError = 422,
    InternalServerError = 500,
}

const headers: Readonly<Record<string, string | boolean>> = {
    Accept: 'application/json',
    'Content-Type': 'application/json; charset=utf-8',
    'Access-Control-Allow-Credentials': true,
    'X-Requested-With': 'XMLHttpRequest',
};

// We can use the following function to inject the JWT token through an interceptor
// We get the `accessToken` from the localStorage that we set when we authenticate
// const injectToken = (config: AxiosRequestConfig): AxiosRequestConfig => {
//     try {
//         const token = localStorage.getItem("accessToken");

//         if (token != null) {
//             config.headers.Authorization = `Bearer ${token}`;
//         }
//         return config;
//     } catch (error) {
//         throw new Error(error);
//     }
// };

class Http {
    private instance: AxiosInstance | null = null;

    private get http(): AxiosInstance {
        return this.instance != null ? this.instance : this.initHttp();
    }

    initHttp() {
        const http = axios.create({
            baseURL: '/',
            headers,
            withCredentials: true,
        });

        //http.interceptors.request.use(injectToken, (error) => Promise.reject(error));

        http.interceptors.response.use(
            (response) => response,
            (error) => {
                const { response } = error;
                return this.handleError(response);
            }
        );

        this.instance = http;
        return http;
    }

    request<R = AxiosResponse>(config: AxiosRequestConfig): Promise<R> {
        return this.http.request(config);
    }

    get<R = AxiosResponse>(
        url: string,
        config?: AxiosRequestConfig
    ): Promise<R> {
        return this.http.get(url, config);
    }

    post<T = any, R = AxiosResponse<T>>(
        url: string,
        data?: T,
        config?: AxiosRequestConfig
    ): Promise<R> {
        return this.http.post<T, R>(url, data, config);
    }

    put<T = any, R = AxiosResponse<T>>(
        url: string,
        data?: T,
        config?: AxiosRequestConfig
    ): Promise<R> {
        return this.http.put<T, R>(url, data, config);
    }

    delete<T = any, R = AxiosResponse<T>>(
        url: string,
        config?: AxiosRequestConfig
    ): Promise<R> {
        return this.http.delete<T, R>(url, config);
    }

    // Handle global app errors
    // We can handle generic app errors depending on the status code
    private handleError(error: AxiosResponse) {
        const { status } = error;
        const errorMessage = extractErrorMessages(error);

        switch (status) {
            case StatusCode.InternalServerError: {
                this.notifyError(errorMessage, 'Internal Server Error');
                break;
            }
            case StatusCode.Forbidden: {
                // Handle Forbidden
                this.notifyError(errorMessage, 'Forbidden');
                break;
            }
            case StatusCode.Unauthorized: {
                // Handle Unauthorized
                // this.notifyError(errorMessage, 'Unauthorized');
                break;
            }
            case StatusCode.TooManyRequests: {
                // Handle TooManyRequests
                this.notifyError(errorMessage, 'Too many requests');
                break;
            }
            case StatusCode.ValidationError: {
                // Handle ValidationError
                this.notifyError(errorMessage, 'Validation Error');
                break;
            }
            case StatusCode.NotFound: {
                // Handle NotFound
                this.notifyError(errorMessage, 'Resource Not Found');
                break;
            }
        }

        console.log('HTTP.TS Handling error: ' + error.statusText);

        return Promise.reject(error);
    }

    private notifyError(error: ErrorResponse, message?: string) {
        Notify.create({
            type: 'negative',
            message: error?.message || message || 'An error occurred',
            textColor: 'white',
            timeout: 5000,
            icon: 'mdi-alert-circle-outline',
        });
    }
}

export const http = new Http();
