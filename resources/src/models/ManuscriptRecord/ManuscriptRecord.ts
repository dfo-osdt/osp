import { http } from '@/api/http';
import { AxiosResponse } from 'axios';
import { BaseResource } from '../BaseResource';

export type ManuscriptRecordType = 'primary' | 'secondary';

export type ManuscriptRecordStatus =
    | 'draft'
    | 'in_review'
    | 'reviewed'
    | 'submitted'
    | 'accepted'
    | 'withdrawn';

type R = AxiosResponse<ManuscriptRecordResource>;

type RList = AxiosResponse<ManuscriptRecordResourceList>;

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
    id: number;
    created_at: string;
    updated_at: string;
    status: ManuscriptRecordStatus;
    user_id: number;
    abstract: string;
    pls_en: string;
    pls_fr: string;
    scientific_implications: string;
    regions_and_species: string;
    relevant_to: string;
    additional_information: string;
    sent_for_review_at: string | null;
    reviewed_at: string | null;
    submitted_to_journal_on: string | null;
    accepted_on: string | null;
    withdrawn_on: string | null;
}

export interface ManuscriptRecordResource extends BaseResource {
    data: ManuscriptRecord;
}
export interface ManuscriptRecordResourceList {
    data: ManuscriptRecordResource[];
}

export class ManuscriptRecordService {
    private static baseURL = 'api/manuscripts';
    /**
     * Get a manuscript record.
     *
     * @param id The manuscript record ID.
     * @returns The manuscript record.
     */
    public static async find(id: number) {
        const response = await http.get<R>(`${this.baseURL}/${id}`);
        return response.data;
    }

    /** Create a manuscript record.
     *
     * @param data The manuscript record data.
     * @returns The created manuscript record.
     */
    public static async create(data: BaseManuscriptRecord) {
        const response = await http.post<BaseManuscriptRecord, R>(
            `${this.baseURL}`,
            data
        );
        return response.data;
    }

    /** Save a manuscript record.
     *
     * @param data The manuscript record data.
     * @returns The saved manuscript record.
     */
    public static async save(data: ManuscriptRecord) {
        const response = await http.put<ManuscriptRecord, R>(
            `${this.baseURL}/${data.id}`,
            data
        );
        return response.data;
    }

    //** Get the logged in users' manuscripts */
    public static async getMyManuscripts() {
        const response = await http.get<RList>(`api/my/manuscripts`);
        return response.data.data;
    }
}
