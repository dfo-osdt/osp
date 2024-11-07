<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    // Ollama configuration for local use
    'use_ollama' => env('OPENAI_USE_OLLAMA', false),
    'ollama_url' => env('OPENAI_OLLAMA_URL'),
    'ollama_model' => env('OPENAI_OLLAMA_MODEL', 'llama3.2'),

];
