import { http } from '@/api/http';
import { SpatieQuery } from '@/api/SpatieQuery';
import { ManuscriptRecordSummaryResource } from '../ManuscriptRecord/ManuscriptRecord';
import { Resource, ResourceList } from '../Resource';
import { UserResource } from '../User/User';

export type ManagementReviewStepDecision = 'none' | 'approved' | 'withheld';
export type ManagementReviewStepStatus = 'pending' | 'deferred' | 'completed';
export type UpdateStep = Pick<ManagementReviewStep, 'comments'>;
export type DecisionStep = {
    comments?: string;
    next_user_id?: number;
};

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
    manuscript_record?: ManuscriptRecordSummaryResource;
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
     * Get all my management review steps.
     */
    public static async listMy(query?: ManagementReviewQuery) {
        let url = 'api/my/management-review-steps';
        if (query) url += `?${query.toQueryString()}`;
        const response = await http.get<RList>(url);

        return response.data;
    }
    /**
     * Get a list of management review steps from a given manuscript record.
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

    /**
     * Update the management review step changes. Currently only the comments
     * will be changed here.
     */
    public static async update(step: ManagementReviewStep) {
        const response = await http.put<UpdateStep, R>(
            `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}`,
            { comments: step.comments }
        );
        return response.data;
    }

    /**
     * Approve the management review step
     *
     * @param step the management review step to approve
     * @param nextUserId the user id of the next user in the workflow - if null, the workflow will be completed
     */
    public static async approve(
        step: ManagementReviewStep,
        nextUserId?: number
    ) {
        // if user exists, add it to the data object
        const data: DecisionStep = {
            comments: step.comments,
        };
        if (nextUserId) data.next_user_id = nextUserId;

        const response = await http.put<DecisionStep, R>(
            `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/approve`,
            data
        );
        return response.data;
    }

    /**
     * Withhold the management review step
     *
     * @param step the management review step to withhold
     * @param nextUserId the user id of the next user in the workflow - if null, the workflow will be completed
     */
    public static async withhold(
        step: ManagementReviewStep,
        nextUserId?: number
    ) {
        // if user exists, add it to the data object
        const data: DecisionStep = {
            comments: step.comments,
        };
        if (nextUserId) data.next_user_id = nextUserId;

        const response = await http.put<DecisionStep, R>(
            `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/withhold`,
            data
        );
        return response.data;
    }

    /**
     * Reassign the management review step.
     *
     * @param step the management review step to defer
     * @param nextUserId the user id of the next user in the workflow.
     */
    public static async reassign(
        step: ManagementReviewStep,
        nextUserId: number
    ) {
        const data: DecisionStep = {
            comments: step.comments,
            next_user_id: nextUserId,
        };

        const response = await http.put<DecisionStep, R>(
            `api/manuscript-records/${step.manuscript_record_id}/management-review-steps/${step.id}/reassign`,
            data
        );
        return response.data;
    }
}

export class ManagementReviewQuery extends SpatieQuery {
    public filterUserId(userId: number) {
        this.filter('user_id', userId);
        return this;
    }
    public filterStatus(status: ManagementReviewStepStatus[]) {
        this.filter('status', status);
        return this;
    }
    public filterDecision(decision: ManagementReviewStepDecision[]) {
        this.filter('decision', decision);
        return this;
    }
    public filterManuscriptRecordTitle(title: string[]) {
        this.filter('manuscriptRecord.title', title);
        return this;
    }

    public includeManuscriptRecord() {
        this.include('manuscriptRecord');
        return this;
    }
    public includeUser() {
        this.include('user');
        return this;
    }
    public sort(sort: ManagementReviewQuerySort, direction: 'asc' | 'desc') {
        super.sort(sort, direction);
        return this;
    }
}

type ManagementReviewQuerySort = 'created_at' | 'updated_at';
