<?php

declare(strict_types=1);

namespace App\Ai\Agents\Concerns;

use Laravel\Ai\Enums\Lab;

/**
 * Ollama generation settings for our agents, so a stock model such as
 * qwen3:8b can be used without a custom Modelfile.
 *
 * Thinking is turned off so a reasoning model's thoughts never end up in
 * the answer we show to users.
 */
trait ConfiguresOllamaGeneration
{
    /**
     * Get provider-specific generation options.
     *
     * @return array<string, mixed>
     */
    public function providerOptions(Lab|string $provider): array
    {
        $provider = $provider instanceof Lab ? $provider->value : $provider;

        if ($provider !== Lab::Ollama->value) {
            return [];
        }

        return [
            'think' => false,
            'num_ctx' => 4096,
            'repeat_penalty' => 1.15,
        ];
    }
}
