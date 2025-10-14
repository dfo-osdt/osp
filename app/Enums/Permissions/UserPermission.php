<?php

namespace App\Enums\Permissions;

enum UserPermission: string
{
    case CREATE_MANUSCRIPT_RECORDS = 'create_manuscript_records';
    case CREATE_PUBLICATIONS = 'create_publications';
    case CREATE_AUTHORS = 'create_authors';
    case UPDATE_AUTHORS = 'update_authors';
    case SYNCHRONIZE_AUTHOR_AFFILIATIONS = 'synchronize_author_affiliations';
    case CREATE_ORGANIZATIONS = 'create_organizations';
    case CREATE_AUTHOR_EMPLOYMENTS = 'create_author_employments';
    case COMPLETE_INTERNTAL_MANAGEMENT_REVIEW = 'complete_interntal_management_review';
    case VIEW_LIBRARIUM = 'view_librarium';
    case VIEW_TELESCOPE = 'view_telescope';
    case VIEW_HORIZON = 'view_horizon';
    case VIEW_PULSE = 'view_pulse';
    case VIEW_ANY_USERS = 'view_any_users';
    case VIEW_ANY_MANUSCRIPT_RECORD = 'view_any_manuscript_record';
    case ADMINISTER_USERS = 'administer_users';
    case PUBLISH_INTERNAL_REPORTS = 'publish_internal_reports';
    case UPDATE_PUBLICATIONS = 'update_publications';

    // Regional MRF permissions - view access
    case CAN_VIEW_NFL_MRFS = 'can_view_nfl_mrfs';
    case CAN_VIEW_MAR_MRFS = 'can_view_mar_mrfs';
    case CAN_VIEW_GLF_MRFS = 'can_view_glf_mrfs';
    case CAN_VIEW_QUE_MRFS = 'can_view_que_mrfs';
    case CAN_VIEW_ONP_MRFS = 'can_view_onp_mrfs';
    case CAN_VIEW_ARC_MRFS = 'can_view_arc_mrfs';
    case CAN_VIEW_PAC_MRFS = 'can_view_pac_mrfs';
    case CAN_VIEW_NCR_MRFS = 'can_view_ncr_mrfs';

    // Regional MRF permissions - edit access
    case CAN_EDIT_NFL_MRFS = 'can_edit_nfl_mrfs';
    case CAN_EDIT_MAR_MRFS = 'can_edit_mar_mrfs';
    case CAN_EDIT_GLF_MRFS = 'can_edit_glf_mrfs';
    case CAN_EDIT_QUE_MRFS = 'can_edit_que_mrfs';
    case CAN_EDIT_ONP_MRFS = 'can_edit_onp_mrfs';
    case CAN_EDIT_ARC_MRFS = 'can_edit_arc_mrfs';
    case CAN_EDIT_PAC_MRFS = 'can_edit_pac_mrfs';
    case CAN_EDIT_NCR_MRFS = 'can_edit_ncr_mrfs';

    // Regional publication permissions - edit access
    case CAN_EDIT_NFL_PUBS = 'can_edit_nfl_pubs';
    case CAN_EDIT_MAR_PUBS = 'can_edit_mar_pubs';
    case CAN_EDIT_GLF_PUBS = 'can_edit_glf_pubs';
    case CAN_EDIT_QUE_PUBS = 'can_edit_que_pubs';
    case CAN_EDIT_ONP_PUBS = 'can_edit_onp_pubs';
    case CAN_EDIT_ARC_PUBS = 'can_edit_arc_pubs';
    case CAN_EDIT_PAC_PUBS = 'can_edit_pac_pubs';
    case CAN_EDIT_NCR_PUBS = 'can_edit_ncr_pubs';

    public static function getRegionalEditorPubEditPermissions(): array
    {
        return [
            self::CAN_EDIT_NFL_PUBS,
            self::CAN_EDIT_MAR_PUBS,
            self::CAN_EDIT_GLF_PUBS,
            self::CAN_EDIT_QUE_PUBS,
            self::CAN_EDIT_ONP_PUBS,
            self::CAN_EDIT_ARC_PUBS,
            self::CAN_EDIT_PAC_PUBS,
            self::CAN_EDIT_NCR_PUBS,
        ];
    }
}
