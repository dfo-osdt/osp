<?php

namespace App\Enums\Permissions;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case DIRECTOR = 'director';
    case EDITOR = 'editor';
    case CHIEF_EDITOR = 'chief_editor';

    public function permissions(): array
    {
        return match ($this) {
            self::AUTHOR => [
                UserPermission::CREATE_MANUSCRIPT_RECORDS->value,
                UserPermission::CREATE_PUBLICATIONS->value,
                UserPermission::CREATE_AUTHORS->value,
                UserPermission::CREATE_ORGANIZATIONS->value,
                UserPermission::CREATE_AUTHOR_EMPLOYMENTS->value,
            ],
            self::DIRECTOR => [
                UserPermission::WITHHOLD_AND_COMPLETE_MANAGEMENT_REVIEW->value,
                // Merge author permissions
                ...self::AUTHOR->permissions(),
            ],
            self::ADMIN => [
                UserPermission::VIEW_LIBRARIUM->value,
                UserPermission::VIEW_TELESCOPE->value,
                UserPermission::VIEW_HORIZON->value,
                UserPermission::VIEW_PULSE->value,
                UserPermission::VIEW_ANY_USERS->value,
                UserPermission::ADMINISTER_USERS->value,
            ],
            self::EDITOR => [
                UserPermission::UPDATE_AUTHORS->value,
                UserPermission::UPDATE_PUBLICATIONS->value,
            ],
            self::CHIEF_EDITOR => [
                UserPermission::PUBLISH_INTERNAL_REPORTS->value,
                // Merge editor permissions
                ...self::EDITOR->permissions(),
            ],
        };
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
