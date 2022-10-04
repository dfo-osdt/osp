import { http } from '@/api/http';
import { AuthorResource } from '../Author/Author';
import { Resource, ResourceList } from '../Resource';

export interface User {
    readonly id: number;
    first_name: string;
    last_name: string;
    email: string;
    author?: AuthorResource;
}

export type UserResource = Resource<User>;
export type UserResourceList = ResourceList<UserResource>;

// User Service
export class UserService {
    /**
     * Get a list of users
     */
    public static async list(query?: string) {
        let url = 'api/users';
        if (query) {
            url += `?${query}`;
        }
        const response = await http.get<UserResourceList>(url);
        return response.data;
    }
}
