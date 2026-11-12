<?php

namespace App\Http\Controllers\OpenAI;

use App\Ai\Agents\PlainLanguageSummaryTranslator;
use App\Ai\Agents\PlainLanguageSummaryWriter;
use App\Http\Controllers\Controller;
use App\Services\RichTextSanitizer;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Exceptions\AiException;

/**
 * Uses the configured AI provider to summarize scientific abstracts into a
 * plain language summary (PLS), and to translate a PLS.
 */
class GeneratePLSController extends Controller
{
    /**
     * Generate a PLS from a scientific abstract.
     */
    public function generate(Request $request): JsonResponse
    {
        ['abstract' => $abstract, 'language' => $language] = $this->validated($request);

        return $this->promptAgent(new PlainLanguageSummaryWriter($language), $abstract);
    }

    /**
     * Translate a PLS into the requested language.
     */
    public function translate(Request $request): JsonResponse
    {
        ['abstract' => $pls, 'language' => $language] = $this->validated($request);

        return $this->promptAgent(new PlainLanguageSummaryTranslator($language), $pls);
    }

    /**
     * Validate the request and return the plain text and the target language.
     *
     * @return array{abstract: string, language: string}
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'abstract' => ['required', 'string', 'max:3000'],
            'locale' => ['required', 'string', 'in:en,fr'],
        ]);

        return [
            // the text could have html tags we don't want
            'abstract' => RichTextSanitizer::toPlain($validated['abstract']),
            'language' => match ($validated['locale']) {
                'fr' => 'French',
                default => 'English',
            },
        ];
    }

    /**
     * Prompt the agent with the text and respond with its answer as the PLS.
     */
    private function promptAgent(Agent $agent, string $text): JsonResponse
    {
        if (! config('osp.ollama.enabled')) {
            return $this->error();
        }

        try {
            $response = $agent->prompt($text);
        } catch (AiException|RequestException $exception) {
            Log::error('AI request failed', ['exception' => $exception]);

            return $this->error();
        }

        if (blank($response->text)) {
            Log::error('AI request returned an empty answer');

            return $this->error();
        }

        return $this->respond($response->text);
    }

    /**
     * Respond with the PLS.
     */
    private function respond(string $pls): JsonResponse
    {
        return response()->json([
            'data' => [
                'pls' => $pls,
            ],
        ]);
    }

    /**
     * Respond with an error.
     */
    private function error(string $message = 'Feature temporarily unavailable.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }
}
