<?php

namespace App\Http\Integrations\Ollama\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CompletionResponseData extends Data
{
    public function __construct(
        public string $model,
        #[MapName('created_at')]
        public string $createdAt,
        public string $response,
        public bool $done,
        public array $context,
        #[MapName('total_duration')]
        public int $totalDuration,
        #[MapName('load_duration')]
        public int $loadDuration,
        #[MapName('prompt_eval_count')]
        public int $promptEvalCount,
        #[MapName('prompt_eval_duration')]
        public int $promptEvalDuration,
        #[MapName('eval_count')]
        public int $evalCount,
        #[MapName('eval_duration')]
        public int $evalDuration,
    ) {}
}
