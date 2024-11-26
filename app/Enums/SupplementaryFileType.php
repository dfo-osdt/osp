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
}
