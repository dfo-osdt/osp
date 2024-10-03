<?php

namespace App\Http\Integrations\Orcid\Enums;

enum ActivitiesScopeEndpoint: string
{

    case EDUCATION = '/education';
    case EMPLOYMENT = '/employment';
    case DISTINCTION = '/distinction';
    case MEMBERSHIP = '/membership';
    case QUALIFICATION = '/qualification';
    case SERVICE = '/service';
    case INVITED_POSITION = '/invited-position';
    case FUNDING = '/funding';
    case WORK = '/work';
    case PEER_REVIEW = '/peer-review';
}