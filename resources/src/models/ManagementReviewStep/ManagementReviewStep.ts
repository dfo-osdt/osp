import { http } from '@/api/http';
import { ManuscriptRecordResource } from '../ManuscriptRecord/ManuscriptRecord';
import { Resource, ResourceList } from '../Resource';
import { UserResource } from '../User/User';

export type ManagementReviewStepDecision = 'none' | 'approved' | 'withheld';
export type ManagementReviewStepStatus = 'pending' | 'deferred' | 'completed';

export interface ManagementReviewStep {
    readonly id: number;
    readonly created_at: string;
    readonly updated_at: string;
    readonly completed_at: string | null;
    comments: string;
    decision: ManagementReviewStepDecision;
    status: ManagementReviewStepStatus;
    manuscript_record_id: number;
    previous_step_id: number | null;
    user_id: number;
    // relationships
    manuscript_record?: ManuscriptRecordResource;
    user?: UserResource;
    previous_step?: ManagementReviewStepResource;
}

export type ManagementReviewStepResource = Resource<ManagementReviewStep>;
export type ManagementReviewStepResourceList =
    ResourceList<ManagementReviewStep>;

type R = ManagementReviewStepResource;
type RList = ManagementReviewStepResourceList;

export class ManagementReviewStepService {
    /**
     * Get a list of management review steps
     *
     * @param manuscriptRecordId the manuscript record to get the management review steps for
     * @returns all existing management review steps for the manuscript record
     */
    public static async list(manuscriptRecordId: number) {
        const response = await http.get<RList>(
            `api/manuscript-records/${manuscriptRecordId}/management-review-steps`
        );
        return response.data;
    }
}
