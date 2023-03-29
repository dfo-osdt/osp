import { http } from '@/api/http';
import { SpatieQuery } from '@/api/SpatieQuery';
import { JournalResource } from '../Journal/Journal';
import { ManuscriptRecordResource } from '../ManuscriptRecord/ManuscriptRecord';
import { PublicationAuthorResource } from '../PublicationAuthor/PublicationAuthor';
import { MediaResource, Resource, ResourceList } from '../Resource';
import { UserResource } from '../User/User';

export type PublicationStatus = 'accepted' | 'published';

export interface Publication {
    readonly id: number;
    readonly created_at: string;
    readonly updated_at: string;
    status: PublicationStatus;
    title: string;
    doi: string | null;
    is_open_access: boolean;
    accepted_on: string | null;
    published_on: string | null;
    embargoed_until: string | null;
    journal_id: number;
    manuscript_record_id: number;
    user_id: number;
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
    public static async list(query?: PublicationQuery) {
        let url = 'api/publications';
        if (query) {
            url += `?${query.toQueryString()}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    /** Find a publication by id */
    public static async find(id: number) {
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
    public static async getMyPublications(query?: PublicationQuery) {
        let url = 'api/my/publications';
        if (query) {
            url += `?${query.toQueryString()}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    /** Get PDF media resource - if it exits */
    public static async getPDF(id: number) {
        const response = await http.get<MediaResource>(
            `api/publications/${id}/pdf`
        );
        return response.data;
    }

    /** Attach a PDF file to the publication */
    public static async attachPDF(file: File, id: number) {
        const formData = new FormData();
        formData.append('pdf', file);
        const response = await http.post<FormData, MediaResource>(
            `api/publications/${id}/pdf`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        );
        return response.data;
    }
}

export class PublicationQuery extends SpatieQuery {
    public filterId(id: number[]) {
        this.filter('id', id);
        return this;
    }

    public filterUserId(userId: number[]) {
        this.filter('user_id', userId);
        return this;
    }

    public filterStatus(status: PublicationStatus[]) {
        this.filter('status', status);
        return this;
    }

    public filterJournalID(journalId: number[]) {
        this.filter('journal_id', journalId);
        return this;
    }

    public filterAuthorID(authorId: number[]) {
        this.filter('publicationAuthors.author_id', authorId);
        return this;
    }

    public filterTitle(title: string) {
        this.filter('title', title);
        return this;
    }

    public filterOpenAccess() {
        this.filter('open_access', true);
        return this;
    }

    public filterNotUnderEmbargo() {
        this.filter('not_under_embargo', true);
        return this;
    }

    public filterUnderEmbargo() {
        this.filter('under_embargo', true);
        return this;
    }

    public filterSecondaryPublication() {
        this.filter('secondary_publication', true);
        return this;
    }

    public sort(sort: PublicationQuerySort, direction: 'asc' | 'desc') {
        super.sort(sort, direction);
        return this;
    }
}

type PublicationQuerySort = 'title' | 'created_at' | 'updated_at';
