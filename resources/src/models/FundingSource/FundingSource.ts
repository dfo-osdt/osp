import { http } from '@/api/http';
import { FunderResource } from '../Funder/Funder';
import { Resource, ResourceList } from '../Resource';

/** Funding Source is a polymorphic relationship which can
 * be attached to a manuscript record or a publication. */
export type FundableType = 'manuscript-records' | 'publications';

export interface FundingSource {
    readonly id: number;
    title: string;
    description: string | null;
    funder_id: number;
    fundable_type: FundableType;
    fundable_id: number;
    funder?: FunderResource;
}

export type FundingSourceCreate = Omit<
    FundingSource,
    'id' | 'funder' | 'fundable_id' | 'fundable_type'
>;
export type FundingSourceResource = Resource<FundingSource>;
export type FundingSourceResourceList = ResourceList<FundingSource>;

export class FundingSourceService {
    private baseUrl: string;

    constructor(relationship: FundableType) {
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
    public async update(data: FundingSource) {
        const response = await http.put<FundingSource, FundingSourceResource>(
            `${this.baseUrl}/${data.fundable_id}/funding-sources/${data.id}`,
            data
        );
        return response.data;
    }

    /** delete a funding source from the funable */
    public async delete(data: FundingSource): Promise<boolean> {
        const response = await http.delete(
            `${this.baseUrl}/${data.fundable_id}/funding-sources/${data.id}`
        );
        // response should be empty with a 204 status code
        return response.status === 204;
    }
}
