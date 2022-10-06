import { http } from '@/api/http';
import { OrganizationResource } from '../Organization/Organization';
import { Resource, ResourceList } from '../Resource';

export interface Author {
    readonly id: number;
    first_name: string;
    last_name: string;
    orcid: string | null;
    email: string;
    user_id: number | null;
    organization_id: number;
    // relationships
    organization?: OrganizationResource;
}

export type AuthorResource = Resource<Author>;
export type AuthorResourceList = ResourceList<AuthorResource>;

// Author Service
export class AuthorService {
    /**
     * Get a list of authors
     * @returns author list
     */
    public static async list(query?: string) {
        let url = 'api/authors';
        if (query) {
            url += `?${query}`;
        }
        const response = await http.get<AuthorResourceList>(url);
        return response.data;
    }

    /**
     * Create an author
     */
    public static async create(author: Author) {
        const response = await http.post<Author, AuthorResource>(
            'api/authors',
            author
        );
        return response.data;
    }
}