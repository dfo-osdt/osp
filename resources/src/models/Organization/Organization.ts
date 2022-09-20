import { http } from '@/api/http';
import { AxiosResponse } from 'axios';
import { Resource, ResourceList } from '../Resource';

export interface Organization {
    id: number;
    is_validated?: boolean;
    name_en: string;
    name_fr: string;
    abbr_en: string | null;
    abbr_fr: string | null;
}

export type OrganizationResource = Resource<Organization>;
export type OrganizationResourceList = ResourceList<Organization>;

type R = AxiosResponse<OrganizationResource>;
type RList = AxiosResponse<OrganizationResourceList>;

export class OrganizationService {
    /** Get a list of organizations
     * @returns organization list
     */
    public static async list(query?: string) {
        let url = 'api/organizations';
        if (query) {
            url += `?${query}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    /** Create a new organization */
    public static async create(organization: Organization) {
        const response = await http.post<Organization, R>(
            'api/organizations',
            organization
        );
        return response.data;
    }
}
