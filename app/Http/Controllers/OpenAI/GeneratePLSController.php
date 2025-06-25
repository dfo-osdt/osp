<?php

namespace App\Http\Controllers\OpenAI;

use App\Http\Controllers\Controller;
use App\Http\Integrations\Ollama\Data\CompletionRequestData;
use App\Http\Integrations\Ollama\OllamaConnector;
use App\Http\Integrations\Ollama\Requests\CompletionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * This controller will use the the OpenAI API to summarize scientific
 * abstracts into a plain language summary (PLS.)
 */
class GeneratePLSController extends Controller
{
    private bool $useOllama = false;

    private ?string $ollamaModel = null;

    public function __construct()
    {
        $this->useOllama = config('osp.ollama.enabled');
        $this->ollamaModel = config('osp.ollama.model');
    }

    /**
     * Handle the incoming request.
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'abstract' => 'required|string|max:3000',
            'locale' => 'required|string|in:en,fr',
        ]);

        $locale = match ($validated['locale']) {
            'fr' => 'French',
            default => 'English',
        };

        // clean up the abstract - it could have html tags we don't want
        $validated['abstract'] = strip_tags($validated['abstract']);

        return $this->generateWithOllama($validated['abstract'], $locale);
    }

    public function translate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'abstract' => 'required|string|max:3000',
            'locale' => 'required|string|in:en,fr',
        ]);

        $locale = match ($validated['locale']) {
            'fr' => 'French',
            default => 'English',
        };

        // clean up the PLS - it could have html tags we don't want
        $validated['abstract'] = strip_tags($validated['abstract']);

        if ($this->useOllama) {
            return $this->translateWithOllama($validated['abstract'], $locale);
        } else {
            return $this->error('Feature temporarily unavailable.');
        }
    }

    /**
     * Query the a local Ollama API to generate a PLS.
     */
    private function generateWithOllama(string $abstract, string $locale): JsonResponse
    {
        $connector = new OllamaConnector;
        $request = new CompletionRequest($this->buildPlsGenerationOllamaRequest($abstract, $locale));
        $response = $connector->send($request);

        if ($response->failed()) {
            Log::error('Ollama request failed', ['response' => $response]);

            return $this->error();
        }

        $completion = $request->createDtoFromResponse($response);
        $pls = $completion->response;

        return $this->respond($pls);
    }

    /**
     * Translate with Ollama
     */
    private function translateWithOllama(string $pls, string $locale): JsonResponse
    {
        $connector = new OllamaConnector;
        $request = new CompletionRequest($this->buildOllamaTranslateRequest($pls, $locale));
        $response = $connector->send($request);

        if ($response->failed()) {
            Log::error('Ollama request failed', ['response' => $response]);

            return $this->error();
        }

        $completion = $request->createDtoFromResponse($response);
        $pls = $completion->response;

        return $this->respond($pls);
    }

    private function buildPlsGenerationOllamaRequest(string $abstract, string $locale): CompletionRequestData
    {

        $prompt = 'Give me plain language summary in '.$locale." for this
        abstract. The scientific abstract is written in a way that is difficult
        for most people to understand. Summarize it so that someone with a grade
        7 to 9 education can understand. Do not include introductory phrases in
        either french or english, your output must contain only the exact
        abstract from the paper. The plain language summary should be around 250
        words, do not exceed 350 words. Be very careful when using quantitative
        language that it keeps the original meaning.\n\n Abstract:
        ###\n".$abstract."\n###";

        return CompletionRequestData::from([
            'model' => $this->ollamaModel,
            'prompt' => $prompt,
            'options' => [
                'temperature' => 0.3,
            ],
            'stream' => false,
        ]);
    }

    private function buildOllamaTranslateRequest(string $abstract, string $locale): CompletionRequestData
    {
        $prompt = "Translate the following plain language summary to {$locale}.
        Do not include introductory phrases, your output must contain only the
        exact translation from this text:
        ###\n".$abstract."\n###";

        return CompletionRequestData::from([
            'model' => $this->ollamaModel,
            'prompt' => $prompt,
            'options' => [
                'temperature' => 0.3,
            ],
            'stream' => false,
        ]);
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
     *
     * @param  string  $message
     */
    private function error($message = 'Feature temporarily unavailable.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }
}
