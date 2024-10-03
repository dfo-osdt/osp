<?php

namespace App\Http\Integrations\Orcid\Enums;

enum ActivitiesScopeListEndpoint: string
{
    // fors lists
    case EDUCATIONS = '/educations';
    case EMPLOYMENTS = '/employments';
    case DISTINCTIONS = '/distinctions';
    case MEMBERSHIPS = '/memberships';
    case QUALIFICATIONS = '/qualifications';
    case SERVICES = '/services';
    case INVITED_POSITIONS = '/invited-positions';
    case FUNDINGS = '/fundings';
    case WORKS = '/works';
    case PEER_REVIEWS = '/peer-reviews';
}
