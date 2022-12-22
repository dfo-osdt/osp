import { http } from '@/api/http';
import { FunderResource } from '../Funder/Funder';
import { Resource, ResourceList } from '../Resource';

/** Funding Source is a polymorphic relationship which can
 * be attached to a manuscript record or a publication. */
export type FundingSourceRelationship = 'manuscript-records' | 'publications';

export interface FundingSource {
    readonly id: number;
    title: string;
    description: string | null;
    funder_id: number;
    funder?: FunderResource;
}

export type FundingSourceCreate = Omit<FundingSource, 'id' | 'funder'>;
export type FundingSourceResource = Resource<FundingSource>;
export type FundingSourceResourceList = ResourceList<FundingSource>;

export class FundingSourceService {
    private baseUrl: string;

    constructor(relationship: FundingSourceRelationship) {
        this.baseUrl = `api/${relationship}`;
    }

    /** list all funding sources for given fundable */
    public async all(fundableId: number) {
        const response = await http.get<FundingSourceResourceList>(
            `${this.baseUrl}/${fundableId}/funding-sources`
        );
        return response.data;
    }

    /** get a specific funding source from the fundable */
    public async find(fundableId: number, fundingSourceId: number) {
        const response = await http.get<FundingSourceResource>(
            `${this.baseUrl}/${fundableId}/funding-sources/${fundingSourceId}`
        );
        return response.data;
    }

    /** create a funding source for the fundable */
    public async create(fundableId: number, data: FundingSourceCreate) {
        const response = await http.post<
            FundingSourceCreate,
            FundingSourceResource
        >(`${this.baseUrl}/${fundableId}/funding-sources`, data);
        return response.data;
    }

    /** update a funding source from the fundable */
    public async update(fundableId: number, data: FundingSource) {
        const response = await http.put<FundingSource, FundingSourceResource>(
            `${this.baseUrl}/${fundableId}/funding-sources/${data.id}`,
            data
        );
        return response.data;
    }
}
