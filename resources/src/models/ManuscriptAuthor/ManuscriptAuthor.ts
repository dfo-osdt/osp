import { http } from '@/api/http';
import { AxiosResponse } from 'axios';
import { AuthorResource } from '../Authors/Authors';
import { OrganizationResource } from '../Organization/Organization';
import { Resource, ResourceList } from '../Resource';

export interface ManuscriptAuthor {
    readonly id: number;
    manuscript_record_id: number;
    author_id: number;
    organization_id: number;
    is_corresponding_author: boolean;
    organization?: OrganizationResource;
    author?: AuthorResource;
}

export type ManuscriptAuthorResource = Resource<ManuscriptAuthor>;
export type ManuscriptAuthorResourceList = ResourceList<ManuscriptAuthor>;

type R = AxiosResponse<ManuscriptAuthorResource>;
type RList = AxiosResponse<ManuscriptAuthorResourceList>;

/**
 * Manuscript Author Service
 */
export class ManuscriptAuthorService {
    /** Get a list of manuscript authors
     * @param manuscriptRecordId
     * @returns {Promise<RList>}
     */
    public static async list(manuscriptRecordId: number) {
        const response = await http.get<RList>(
            `api/manuscript-records/${manuscriptRecordId}/manuscript-authors`
        );
        return response.data;
    }

    /**
     * Delete a manuscript author
     * @param manuscriptAuthor
     * @returns boolean
     */
    public static async delete(manuscriptAuthor: ManuscriptAuthor) {
        const { manuscript_record_id, id } = manuscriptAuthor;
        const response = await http.delete(
            `api/manuscript-records/${manuscript_record_id}/manuscript-authors/${id}`
        );
        return response.status === 204;
    }
}
