<?php

namespace App\Enums;

enum SupplementaryFileType: string
{
    const MANUSCRIPT_RECORD_FORM = 'manuscript_record_form';

    const AUTHOR_AGREEMENT = 'author_agreement';

    const JOINT_COPYRIGHT_AGREEMENT = 'joint_copyright_agreement';

    const PREPRINT = 'preprint';

    const AUTHORS_ACCEPTED_MANUSCRIPT = 'authors_accepted_manuscript';

    const ERRATA = 'errata';

    const OTHER = 'other';
}
