<?php

namespace App\Http\Integrations\Orcid\Enums;

enum PersonScopeEndpoint: string
{
    case PERSON = '/person';
    case PERSONAL_DETAILS = '/personal-details';
    case OTHER_NAMES = '/other-names';
    case ADDRESS = '/address';
    case KEYWORDS = '/keywords';
    case WEBSITES = '/researcher-urls';
    case EXTERNAL_IDENTIFIERS = '/external-identifiers';
    case EMAILS = '/email';
}
