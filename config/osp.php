<?php

return [

    /*
     * The allowed registration email domains for new users.
     */
    'allowed_registration_email_domains' => explode(',', strtolower(env('ALLOWED_REGISTRATION_EMAIL_DOMAINS', 'dfo-mpo.gc.ca'))),

    /**
     * The email address to send manuscript submissions
     */
    'manuscript_submission_email' => env('MANUSCRIPT_SUBMISSION_EMAIL', null),

    /*
     The default pagination for API requests. This is used by the
     PaginationLimitTrait to set the default limit for API requests.
    */
    'api_pagination' => [
        'default' => 10,
        'max' => 100,
    ],

    /* Management review variables */
    'management_review' => [
        'decision_expected_business_days' => 10,
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

    'ollama' => [
        'enabled' => env('USE_OLLAMA', false),
        'url' => env('OLLAMA_URL'),
        'model' => env('OLLAMA_MODEL', 'llama3.2'),
    ],

];
