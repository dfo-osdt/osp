<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Rewrites a scientific abstract as a plain language summary (PLS).
 * The abstract is sent as the prompt.
 */
#[Temperature(0.3)]
#[Timeout(120)]
class PlainLanguageSummaryWriter implements Agent
{
    use Promptable;

    /**
     * @param  string  $language  The language to write the summary in, e.g. "English" or "French".
     */
    public function __construct(public string $language) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "Give me a plain language summary in {$this->language} for the
        scientific abstract you are given. The scientific abstract is written in
        a way that is difficult for most people to understand. Summarize it so
        that someone with a grade 7 to 9 education can understand. Do not include
        introductory phrases in either French or English, your output must
        contain only the plain language summary. The plain language summary
        should be around 250 words, do not exceed 350 words. Be very careful when
        using quantitative language that it keeps the original meaning.";
    }
}
