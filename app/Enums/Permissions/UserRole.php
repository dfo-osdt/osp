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

    // Regional observers roles
    case NFL_OBSERVER = 'nfl_observer';
    case MAR_OBSERVER = 'mar_observer';
    case GLF_OBSERVER = 'glf_observer';
    case QUE_OBSERVER = 'que_observer';
    case ONP_OBSERVER = 'onp_observer';
    case ARC_OBSERVER = 'arc_observer';
    case PAC_OBSERVER = 'pac_observer';
    case NCR_OBSERVER = 'ncr_observer';

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
                // Merge Regional Editors permissions
                ...self::getPermissionsForRoles(self::getAllRegionalRoles()),
            ],
            self::EDITOR => [
                UserPermission::UPDATE_AUTHORS,
                UserPermission::UPDATE_PUBLICATIONS,
                UserPermission::VIEW_ANY_MANUSCRIPT_RECORD,
                UserPermission::SYNCHRONIZE_AUTHOR_AFFILIATIONS,
            ],
            self::CHIEF_EDITOR => [
                UserPermission::PUBLISH_INTERNAL_REPORTS,
                UserPermission::DELETE_PUBLICATIONS,
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
            self::NFL_OBSERVER => [
                UserPermission::CAN_VIEW_NFL_MRFS,
            ],
            self::MAR_OBSERVER => [
                UserPermission::CAN_VIEW_MAR_MRFS,
            ],
            self::GLF_OBSERVER => [
                UserPermission::CAN_VIEW_GLF_MRFS,
            ],
            self::QUE_OBSERVER => [
                UserPermission::CAN_VIEW_QUE_MRFS,
            ],
            self::ONP_OBSERVER => [
                UserPermission::CAN_VIEW_ONP_MRFS,
            ],
            self::ARC_OBSERVER => [
                UserPermission::CAN_VIEW_ARC_MRFS,
            ],
            self::PAC_OBSERVER => [
                UserPermission::CAN_VIEW_PAC_MRFS,
            ],
            self::NCR_OBSERVER => [
                UserPermission::CAN_VIEW_NCR_MRFS,
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
            // Regional observer labels
            self::NFL_OBSERVER => 'Newfoundland and Labrador Observer',
            self::MAR_OBSERVER => 'Maritimes Observer',
            self::GLF_OBSERVER => 'Gulf Observer',
            self::QUE_OBSERVER => 'Quebec Observer',
            self::ONP_OBSERVER => 'Ontario and Prairie Observer',
            self::ARC_OBSERVER => 'Arctic Observer',
            self::PAC_OBSERVER => 'Pacific Observer',
            self::NCR_OBSERVER => 'National Capital Region Observer',
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

    public static function getRegionalObserverRoles(): array
    {
        return [
            self::NFL_OBSERVER,
            self::MAR_OBSERVER,
            self::GLF_OBSERVER,
            self::QUE_OBSERVER,
            self::ONP_OBSERVER,
            self::ARC_OBSERVER,
            self::PAC_OBSERVER,
            self::NCR_OBSERVER,
        ];
    }

    public static function getAllRegionalRoles(): array
    {
        return array_merge(
            self::getRegionalEditorRoles(),
            self::getRegionalObserverRoles()
        );
    }

    public static function getPermissionsForRoles(array $roles): array
    {
        return collect($roles)
            ->flatMap(fn (self $role): array => $role->permissions())
            ->unique(fn ($permission) => $permission->value)
            ->all();
    }
}
