import { Organization } from '../Organization/Organization';

export interface Author {
    readonly id: number;
    first_name: string;
    last_name: string;
    orcid: string | null;
    email: string;
    organization_id: number;
    // relationships
    organization?: Organization;
}
