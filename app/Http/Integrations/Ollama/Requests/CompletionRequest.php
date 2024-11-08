<?php

namespace App\Http\Integrations\Ollama\Requests;

use App\Http\Integrations\Ollama\Data\CompletionRequestData;
use App\Http\Integrations\Ollama\Data\CompletionResponseData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CompletionRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(readonly protected CompletionRequestData $data) {}

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): CompletionResponseData
    {
        $data = $response->array();

        return CompletionResponseData::from($data);
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/generate';
    }
}
