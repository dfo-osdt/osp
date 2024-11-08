<?php

namespace App\Http\Integrations\Ollama\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CompletionRequestData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public string|Optional $suffix,
        public string|Optional $format,
        #[MapName('options')]
        public OllamaOptionsData|Optional $options,
        public string|Optional $system,
        public string|Optional $template,
        public array|Optional $context,
        public bool $stream,
        public string|Optional $raw,
        #[MapName('keep_alive')]
        public string|Optional $keepAlive
    ) {}
}
