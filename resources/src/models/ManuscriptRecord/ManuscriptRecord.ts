import { http } from '@/api/http';
import { ManuscriptAuthorResource } from '@/models/ManuscriptAuthor/ManuscriptAuthor';
import { PublicationResource } from '../Publication/Publication';
import { Region } from '../Region/Region';
import { Resource, ResourceList, Media } from '../Resource';
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
    readonly created_at: string;
    readonly updated_at: string;
    status: ManuscriptRecordStatus;
    user_id: number;
    abstract: string;
    pls: string;
    scientific_implications: string;
    regions_and_species: string;
    relevant_to: string;
    additional_information: string;
    readonly sent_for_review_at: string | null;
    readonly reviewed_at: string | null;
    submitted_to_journal_on: string | null;
    accepted_on: string | null;
    withdrawn_on: string | null;
    manuscript_pdf: Media | null;
    // relationships
    region?: Region;
    manuscript_authors?: ManuscriptAuthorResource[];
    user?: UserResource;
    publication?: PublicationResource;
    // special model permissions
    can_attach_manuscript: boolean;
}

export type ManuscriptRecordResource = Resource<ManuscriptRecord>;
export type ManuscriptRecordResourceList = ResourceList<ManuscriptRecord>;

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
            `${this.baseURL}/${id}`
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

    /** Attach a PDF file to the manuscript record */
    public static async attachPDF(file: File, id: number) {
        const formData = new FormData();
        formData.append('pdf', file);
        const response = await http.post<FormData, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/pdf`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        );
        return response.data;
    }

    /** Submit this manuscript for managemment review */
    public static async submitForReview(id: number, userId: number) {
        const data = {
            reviewer_user_id: userId,
        };
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/submit-for-review`,
            data
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
            data
        );
        return response.data;
    }

    /** Mark this manuscript as being accepted by a journal */
    public static async accepted(
        id: number,
        submittedOn: string,
        acceptedOn: string,
        journalId: number
    ) {
        const data = {
            submitted_to_journal_on: submittedOn,
            accepted_on: acceptedOn,
            journal_id: journalId,
        };
        const response = await http.put<any, ManuscriptRecordResource>(
            `${this.baseURL}/${id}/accepted`,
            data
        );
        return response.data;
    }

    //** Get the logged in users' manuscripts */
    public static async getMyManuscripts() {
        const response = await http.get<ManuscriptRecordResourceList>(
            `api/my/manuscript-records`
        );
        return response.data.data;
    }
}
