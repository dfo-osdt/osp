import { http } from '@/api/http';
import { JournalResource } from '../Journal/Journal';
import { ManuscriptRecordResource } from '../ManuscriptRecord/ManuscriptRecord';
import { PublicationAuthorResource } from '../PublicationAuthor/PublicationAuthor';
import { Media, Resource, ResourceList } from '../Resource';
import { UserResource } from '../User/User';

export type PublicationStatus = 'accepted' | 'published';

export interface Publication {
    readonly id: number;
    readonly created_at: string;
    readonly updated_at: string;
    status: PublicationStatus;
    title: string;
    doi: string;
    is_open_access: boolean;
    accepted_on: string | null;
    published_on: string | null;
    embargoed_until: string | null;
    journal_id: number;
    manuscript_record_id: number;
    user_id: number;
    publication_pdf: Media | null;
    // relationships
    journal?: JournalResource;
    manuscript_record?: ManuscriptRecordResource;
    user?: UserResource;
    publication_authors?: PublicationAuthorResource[];
}

export type PublicationCreate = Omit<
    Publication,
    | 'id'
    | 'created_at'
    | 'updated_at'
    | 'manuscript_record_id'
    | 'user_id'
    | 'publication_pdf'
>;

export type PublicationResource = Resource<Publication>;
export type PublicationResourceList = ResourceList<Publication>;

export type R = PublicationResource;
export type RList = PublicationResourceList;

/**
 * Publication Service
 */
export class PublicationService {
    /** Get a list of publications */
    public static async list(query?: string) {
        let url = 'api/publications';
        if (query) {
            url += `?${query}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    /** Get a publication by id */
    public static async get(id: number) {
        const response = await http.get<R>(`api/publications/${id}`);
        return response.data;
    }

    /** Create a new publication */
    public static async create(data: PublicationCreate) {
        const response = await http.post<PublicationCreate, R>(
            `api/publications`,
            data
        );
        return response.data;
    }

    /** Update a publication */
    public static async update(id: number, data: Publication) {
        const response = await http.put<Publication, R>(
            `api/publications/${id}`,
            data
        );
        return response.data;
    }

    /** Delete a publication */
    public static async delete(id: number) {
        const response = await http.delete(`api/publications/${id}`);
        return response.status === 204;
    }

    /** Get a list of publications for logged in user */
    public static async getMyPublications() {
        const response = await http.get<RList>(`api/my/publications?limit=100`);
        return response.data.data;
    }
}
