import { http } from '@/api/http';
import { AuthorResource } from '../Authors/Author';
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

type R = ManuscriptAuthorResource;
type RList = ManuscriptAuthorResourceList;

/**
 * Manuscript Author Service
 */
export class ManuscriptAuthorService {
    /** Get a list of manuscript authors
     * @param manuscriptRecordId
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
     */
    public static async delete(manuscriptAuthor: ManuscriptAuthor) {
        const { manuscript_record_id, id } = manuscriptAuthor;
        const response = await http.delete(
            `api/manuscript-records/${manuscript_record_id}/manuscript-authors/${id}`
        );
        return response.status === 204;
    }

    /**
     * Add a manuscript author
     * @param manuscriptRecordId - manuscript to add this author to
     * @param authorId - author to associate with this manuscript
     * @param isCorrespondingAuthor - is this author the corresponding author
     */
    public static async create(
        manuscriptRecordId: number,
        authorId: number,
        isCorrespondingAuthor: boolean
    ) {
        const url = `api/manuscript-records/${manuscriptRecordId}/manuscript-authors`;
        const response = await http.post<any, R>(url, {
            author_id: authorId,
            is_corresponding_author: isCorrespondingAuthor,
        });
        return response.data;
    }

    /** Update manuscript author */
    public static async update(manuscriptAuthor: ManuscriptAuthor) {
        const { manuscript_record_id, id } = manuscriptAuthor;
        const url = `api/manuscript-records/${manuscript_record_id}/manuscript-authors/${id}`;
        const response = await http.put<any, R>(url, {
            is_corresponding_author: manuscriptAuthor.is_corresponding_author,
        });
        return response.data;
    }
}
