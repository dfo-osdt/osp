<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Ai\Agents\Concerns\ConfiguresOllamaGeneration;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasProviderOptions;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Translates a plain language summary (PLS) into another language.
 * The summary is sent as the prompt.
 */
#[Temperature(0.3)]
#[Timeout(120)]
class PlainLanguageSummaryTranslator implements Agent, HasProviderOptions
{
    use ConfiguresOllamaGeneration;
    use Promptable;

    /**
     * @param  string  $language  The language to translate into, e.g. "English" or "French".
     */
    public function __construct(public string $language) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "Translate the plain language summary you are given to {$this->language}.
        Do not include introductory phrases, your output must contain only the
        exact translation of the text. Keep every number, size, and comparison
        exactly as written, and do not add or remove information. Write plain text
        in paragraphs only, without Markdown formatting. When translating to French,
        use Canadian French as used by Fisheries and Oceans Canada, including its
        official French names for species, places, and programs (for example,
        \"pétoncle géant\", not \"coquille Saint-Jacques\").";
    }
}
