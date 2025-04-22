<?php

namespace App\Enums\Permissions;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case DIRECTOR = 'director';
    case EDITOR = 'editor';
    case CHIEF_EDITOR = 'chief_editor';

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
            ],
            self::CHIEF_EDITOR => [
                UserPermission::PUBLISH_INTERNAL_REPORTS,
                // Merge editor permissions
                ...self::EDITOR->permissions(),
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
        };
    }
}
