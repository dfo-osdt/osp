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
