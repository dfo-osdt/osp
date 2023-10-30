import { http } from '@/api/http';
import { SpatieQuery } from '@/api/SpatieQuery';
import { ManuscriptAuthorResource } from '@/models/ManuscriptAuthor/ManuscriptAuthor';
import { PublicationResource } from '../Publication/Publication';
import { Region } from '../Region/Region';
import {
    Resource,
    ResourceList,
    MediaResource,
    MediaResourceList,
} from '../Resource';
import { UserResource } from '../User/User';

export type ManuscriptRecordType = 'primary' | 'secondary';

export type ManuscriptRecordStatus =
    | 'draft'
    | 'in_review'
    | 'reviewed'
    | 'submitted'
    | 'accepted'
    | 'withdrawn'
    | 'withheld';

/**
 * The minimum set of data required to create a new manuscript record.
 */
export interface BaseManuscriptRecord {
    title: string;
    region_id: number;
    type: ManuscriptRecordType;
}

/**
 * Manuscript record data.
 */
export interface ManuscriptRecord extends BaseManuscriptRecord {
    readonly id: number;
    readonly ulid: string;
    readonly created_at: string;
    readonly updated_at: string;
    status: ManuscriptRecordStatus;
    user_id: number;
    abstract: string;
    pls: string;
    relevant_to: string;
    additional_information: string;
    potential_public_interest: boolean;
    readonly sent_for_review_at: string | null;
    readonly reviewed_at: string | null;
    submitted_to_journal_on: string | null;
    accepted_on: string | null;
    withdrawn_on: string | null;
    // relationships
    region?: Region;
    manuscript_authors?: ManuscriptAuthorResource[];
    user?: UserResource;
    publication?: PublicationResource;
    // special model permissions
    can_attach_manuscript: boolean;
}

export type ManuscriptRecordSummary = Omit<
    ManuscriptRecord,
    | 'abstract'
    | 'pls'
    | 'relevant_to'
    | 'additional_information'
    | 'manuscript_pdf'
    | 'publication'
    | 'can_attach_manuscript'
>;

export type ManuscriptRecordResource = Resource<ManuscriptRecord>;
export type ManuscriptRecordResourceList = ResourceList<ManuscriptRecord>;
export type ManuscriptRecordSummaryResource = Resource<ManuscriptRecordSummary>;
export type ManuscriptRecordSummaryResourceList =
    ResourceList<ManuscriptRecordSummary>;

export class ManuscriptRecordService {
    private static baseURL = 'api/manuscript-records';
    /**
     * Get a manuscript record.
     *
     * @param id The manuscript record ID.
     * @returns The manuscript record.
     */
    public static async find(id: number) {
        const response = await http.get<ManuscriptRecordResource>(
            `${this.baseURL}/${id}`,
        );
        return response.data;
    }

    /** Create a manuscript record.
     *
     * @param data The manuscript record data.
     * @returns The created manuscript record.
     */
    public static async create(data: BaseManuscriptRecord) {
        const response = await http.post<
            BaseManuscriptRecord,
            ManuscriptRecordResource
        >(`${this.baseURL}`, data);
        return response.data;
    }

    /** Save a manuscript record.
     *
     * @param data The manuscript record data.
     * @returns The saved manuscript record.
     */
    public static async save(data: ManuscriptRecord) {
        const response = await http.put<
            ManuscriptRecord,
            ManuscriptRecordResource
        >(`${this.baseURL}/${data.id}`, data);
        return response.data;
    }

    /** Delete a manuscript record
     * @returns true if the manuscript record was deleted.
     */
    public static async delete(id: number) {
        const response = await http.delete(`${this.baseURL}/${id}`);
        return response.status === 204;
    }

    /** Attach a PDF file to the manuscript record */
    public static async attachPDF(file: File, id: number) {
        const formData = new FormData();
        formData.append('pdf', file);
        const response = await http.post<FormData, MediaResource>(
            `${this.baseURL}/${id}/files`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        );
        return response.data;
    }

    /** List all PDF files associated with this manuscript */
    public static async listPDFs(id: number) {
        const response = await http.get<MediaResourceList>(
            `${this.baseURL}/${id}/files`,
        );
        return response.data;
    }

    /**
     * Delete a PDF file associated with this manuscript
     * If no uuid is provided, the manuscript's manuscript_pdf will be used.
     */
    public static async deletePDF(id: number, uuid: string) {
        const response = await http.delete(
            `${this.baseURL}/${id}/files/${uuid}`,
        );
        return response.status === 204;
    }

    /** Submit this manuscript for managemment review */
    public static async submitForReview(id: number, userId: number) {
        const data = {
            reviewer_user_id: userId,
        };
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/submit-for-review`,
            data,
        );
        return response.data;
    }

    /** Mark this manuscript as being submitted to a journal */
    public static async submitted(id: number, date: string) {
        const data = {
            submitted_to_journal_on: date,
        };
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/submitted`,
            data,
        );
        return response.data;
    }

    /** Mark this manuscript as being accepted by a journal */
    public static async accepted(
        id: number,
        submittedOn: string,
        acceptedOn: string,
        journalId: number,
    ) {
        const data = {
            submitted_to_journal_on: submittedOn,
            accepted_on: acceptedOn,
            journal_id: journalId,
        };
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/accepted`,
            data,
        );
        return response.data;
    }

    // Withdraw the manuscript
    public static async withdraw(id: number) {
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/withdraw`,
        );
        return response.data;
    }

    /** Get the logged in users' manuscripts */
    public static async getMyManuscripts(query?: ManuscriptQuery) {
        let url = 'api/my/manuscript-records';
        if (query) {
            url += `?${query.toQueryString()}`;
        }
        const response =
            await http.get<ManuscriptRecordSummaryResourceList>(url);
        return response.data;
    }
}

export class ManuscriptQuery extends SpatieQuery {
    public filterUserId(userId: number[]): this {
        this.filter('user_id', userId);
        return this;
    }

    public filterTitle(title: string[]): this {
        this.filter('title', title);
        return this;
    }

    public filterRegionId(regionId: number[]): this {
        this.filter('region_id', regionId);
        return this;
    }

    public filterType(type: ManuscriptRecordType[]): this {
        this.filter('type', type);
        return this;
    }

    public filterStatus(status: ManuscriptRecordStatus[]): this {
        this.filter('status', status);
        return this;
    }

    public includeManagementReview(): this {
        this.custom('include-reviews', 'true');
        return this;
    }

    public sort(name: ManuscriptQuerySort, direction: 'asc' | 'desc'): this {
        super.sort(name, direction);
        return this;
    }
}

type ManuscriptQuerySort =
    | 'created_at'
    | 'updated_at'
    | 'title'
    | 'status'
    | 'type';
