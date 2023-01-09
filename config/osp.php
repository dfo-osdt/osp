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

];
