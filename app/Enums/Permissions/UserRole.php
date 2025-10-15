<?php

namespace App\Enums\Permissions;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case DIRECTOR = 'director';
    case EDITOR = 'editor';
    case CHIEF_EDITOR = 'chief_editor';

    // Regional editor roles
    case NFL_EDITOR = 'nfl_editor';
    case MAR_EDITOR = 'mar_editor';
    case GLF_EDITOR = 'glf_editor';
    case QUE_EDITOR = 'que_editor';
    case ONP_EDITOR = 'onp_editor';
    case ARC_EDITOR = 'arc_editor';
    case PAC_EDITOR = 'pac_editor';
    case NCR_EDITOR = 'ncr_editor';

    /**
     * Get the permissions associated with the role
     *
     * @return UserPermission[]
     */
    public function permissions(): array
    {
        return match ($this) {
            self::AUTHOR => [
                UserPermission::CREATE_MANUSCRIPT_RECORDS,
                UserPermission::CREATE_PUBLICATIONS,
                UserPermission::CREATE_AUTHORS,
                UserPermission::CREATE_ORGANIZATIONS,
                UserPermission::CREATE_AUTHOR_EMPLOYMENTS,
            ],
            self::DIRECTOR => [
                UserPermission::COMPLETE_INTERNTAL_MANAGEMENT_REVIEW,
                UserPermission::VIEW_ANY_MANUSCRIPT_RECORD,
                // Merge author permissions
                ...self::AUTHOR->permissions(),
            ],
            self::ADMIN => [
                UserPermission::VIEW_LIBRARIUM,
                UserPermission::VIEW_TELESCOPE,
                UserPermission::VIEW_HORIZON,
                UserPermission::VIEW_PULSE,
                UserPermission::VIEW_ANY_USERS,
                UserPermission::ADMINISTER_USERS,
            ],
            self::EDITOR => [
                UserPermission::UPDATE_AUTHORS,
                UserPermission::UPDATE_PUBLICATIONS,
                UserPermission::VIEW_ANY_MANUSCRIPT_RECORD,
                UserPermission::SYNCHRONIZE_AUTHOR_AFFILIATIONS,
            ],
            self::CHIEF_EDITOR => [
                UserPermission::PUBLISH_INTERNAL_REPORTS,
                // Merge editor permissions
                ...self::EDITOR->permissions(),
            ],
            // Regional editor permissions
            self::NFL_EDITOR => [
                UserPermission::CAN_VIEW_NFL_MRFS,
                UserPermission::CAN_EDIT_NFL_MRFS,
                UserPermission::CAN_EDIT_NFL_PUBS,
            ],
            self::MAR_EDITOR => [
                UserPermission::CAN_VIEW_MAR_MRFS,
                UserPermission::CAN_EDIT_MAR_MRFS,
                UserPermission::CAN_EDIT_MAR_PUBS,
            ],
            self::GLF_EDITOR => [
                UserPermission::CAN_VIEW_GLF_MRFS,
                UserPermission::CAN_EDIT_GLF_MRFS,
                UserPermission::CAN_EDIT_GLF_PUBS,
            ],
            self::QUE_EDITOR => [
                UserPermission::CAN_VIEW_QUE_MRFS,
                UserPermission::CAN_EDIT_QUE_MRFS,
                UserPermission::CAN_EDIT_QUE_PUBS,
            ],
            self::ONP_EDITOR => [
                UserPermission::CAN_VIEW_ONP_MRFS,
                UserPermission::CAN_EDIT_ONP_MRFS,
                UserPermission::CAN_EDIT_ONP_PUBS,
            ],
            self::ARC_EDITOR => [
                UserPermission::CAN_VIEW_ARC_MRFS,
                UserPermission::CAN_EDIT_ARC_MRFS,
                UserPermission::CAN_EDIT_ARC_PUBS,
            ],
            self::PAC_EDITOR => [
                UserPermission::CAN_VIEW_PAC_MRFS,
                UserPermission::CAN_EDIT_PAC_MRFS,
                UserPermission::CAN_EDIT_PAC_PUBS,
            ],
            self::NCR_EDITOR => [
                UserPermission::CAN_VIEW_NCR_MRFS,
                UserPermission::CAN_EDIT_NCR_MRFS,
                UserPermission::CAN_EDIT_NCR_PUBS,
            ],
        };

    }

    /**
     * Get the permission values associated with the role
     *
     * @return string[]
     */
    public function permissionValues(): array
    {
        return collect($this->permissions())->map(fn ($permission) => $permission->value)->all();
    }

    public function label(): string
    {
        return match ($this) {
            self::AUTHOR => 'Author',
            self::DIRECTOR => 'Director',
            self::ADMIN => 'Admin',
            self::EDITOR => 'Editor',
            self::CHIEF_EDITOR => 'Chief Editor',
            // Regional editor labels
            self::NFL_EDITOR => 'Newfoundland and Labrador Editor',
            self::MAR_EDITOR => 'Maritimes Editor',
            self::GLF_EDITOR => 'Gulf Editor',
            self::QUE_EDITOR => 'Quebec Editor',
            self::ONP_EDITOR => 'Ontario and Prairie Editor',
            self::ARC_EDITOR => 'Arctic Editor',
            self::PAC_EDITOR => 'Pacific Editor',
            self::NCR_EDITOR => 'National Capital Region Editor',
        };
    }

    public static function getRegionalEditorRoles(): array
    {
        return [
            self::NFL_EDITOR,
            self::MAR_EDITOR,
            self::GLF_EDITOR,
            self::QUE_EDITOR,
            self::ONP_EDITOR,
            self::ARC_EDITOR,
            self::PAC_EDITOR,
            self::NCR_EDITOR,
        ];
    }
}
