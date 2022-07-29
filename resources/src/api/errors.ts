import { Axios, AxiosResponse } from 'axios';

interface ErrorMessage {
    field: string;
    messages: any;
}

export interface ErrorResponse {
    code: number;
    message: string;
    errors: ErrorMessage[];
}

export const extractErrorMessages = (
    errorResponse: AxiosResponse
): ErrorResponse => {
    console.log('hello?');
    const errors: ErrorResponse = {
        code: errorResponse.status,
        message: errorResponse.data?.message,
        errors: [],
    };

    if (errorResponse.data.errors) {
        for (const [key, value] of Object.entries(errorResponse.data.errors)) {
            errors.errors.push({ field: key, messages: value });
        }
    }
    return errors;
};
