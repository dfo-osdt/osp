import { http } from '@/api/http';
import { Locale } from '@/api/Locale';
import { AuthorResource } from '../Author/Author';
import { Resource, ResourceList } from '../Resource';

export interface User {
    readonly id: number;
    first_name: string;
    last_name: string;
    email: string;
    locale: Locale;
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

    public static async get(id: number) {
        const response = await http.get<UserResource>(`api/users/${id}`);
        return response.data;
    }

    public static async update(
        id: number,
        firstName: string,
        lastName: string,
        locale: Locale
    ) {
        const response = await http.put<any, UserResource>(`api/users/${id}`, {
            first_name: firstName,
            last_name: lastName,
            locale,
        });
        return response.data;
    }
}
