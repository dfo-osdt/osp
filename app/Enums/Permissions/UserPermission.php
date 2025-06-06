<?php

namespace App\Enums\Permissions;

enum UserPermission: string
{
    case CREATE_MANUSCRIPT_RECORDS = 'create_manuscript_records';
    case CREATE_PUBLICATIONS = 'create_publications';
    case CREATE_AUTHORS = 'create_authors';
    case UPDATE_AUTHORS = 'update_authors';
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
}
