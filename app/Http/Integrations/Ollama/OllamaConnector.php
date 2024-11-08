<?php

namespace App\Http\Integrations\Ollama;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class OllamaConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('osp.ollama.url').'/api';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
