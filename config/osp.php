<?php

return [

    /*
     The default pagination for API requests. This is used by the
     PaginationLimitTrait to set the default limit for API requests.
    */
    'api_pagination' => [
        'default' => 10,
        'max' => 100,
    ],

    /*
        The OpenAI API key.
    */
    'openai_api_key' => env('OPENAI_API_KEY'),

    /*
        The default organization to use when creating new authors.
    */
    'default_organization' => 'Fisheries and Oceans Canada',

    /**
     * The ORCID integration
     */
    'orcid' => [
        'use_sandbox' => env('ORCID_USE_SANDBOX', false),
        'client_id' => env('ORCID_CLIENT_ID'),
        'client_secret' => env('ORCID_CLIENT_SECRET'),
        'redirect_uri' => env('ORCID_REDIRECT_URI'),
    ],

    'ohdear' => [
        'enabled' => env('OHDEAR_ENABLED', false),
        'url' => env('OHDEAR_URL'),
    ],
];
