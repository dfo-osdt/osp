import { http } from '@/api/http';
import { Resource, ResourceList } from '../Resource';

export interface Journal {
    readonly id: number;
    title_en: string;
    title_fr: string | null;
    publisher: string;
}

export type JournalResource = Resource<Journal>;
export type JournalResourceList = ResourceList<Journal>;

type R = JournalResource;
type RList = JournalResourceList;

/**
 * Journal Service
 */
export class JournalService {
    /** Get a list of journals */
    public static async list(query?: string) {
        let url = 'api/journals';
        if (query) {
            url += `?${query}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    /** Get a journal by id */
    public static async get(id: number) {
        const response = await http.get<R>(`api/journals/${id}`);
        return response.data;
    }
}
