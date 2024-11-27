<?php

namespace App\Enums;

enum SupplementaryFileType: string
{
    case MANUSCRIPT_RECORD_FORM = 'manuscript_record_form';
    case AUTHOR_AGREEMENT = 'author_agreement';
    case JOINT_COPYRIGHT_AGREEMENT = 'joint_copyright_agreement';
    case PREPRINT = 'preprint';
    case AUTHORS_ACCEPTED_MANUSCRIPT = 'authors_accepted_manuscript';
    case ERRATA = 'errata';
    case OTHER = 'other';

    public static function getProtectedAFileTypes(): array
    {
        return [
            self::MANUSCRIPT_RECORD_FORM,
            self::AUTHOR_AGREEMENT,
            self::JOINT_COPYRIGHT_AGREEMENT,
        ];
    }

    public static function isProtectedA(self $type): bool
    {
        return in_array($type, self::getProtectedAFileTypes());
    }
}
