import { http } from './http';

export type PlsRequest = {
    abstract: string;
};

export type PlsResponse = {
    data: {
        pls: string;
    };
};

export class UtilityService {
    /**
     * Get a plain language summary from the given abstract text.
     */
    public static async generatePls(abstract: string): Promise<PlsResponse> {
        const response = await http.post<PlsRequest, PlsResponse>(
            `api/utils/generate-pls`,
            { abstract }
        );

        return response.data;
    }
}
