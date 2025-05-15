<?php

namespace App\Http\Controllers\OpenAI;

use App\Http\Controllers\Controller;
use App\Http\Integrations\Ollama\Data\CompletionRequestData;
use App\Http\Integrations\Ollama\OllamaConnector;
use App\Http\Integrations\Ollama\Requests\CompletionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Laravel\Facades\OpenAI as OpenAILaravel;

/**
 * This controller will use the the OpenAI API to summarize scientific
 * abstracts into a plain language summary (PLS.)
 */
class GeneratePLSController extends Controller
{
    // When working on this prompt refer to this article:
    // https://help.openai.com/en/articles/6654000-best-practices-for-prompt-engineering-with-openai-api

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
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'abstract' => 'required|string|max:3000',
        ]);

        // clean up the abstract - it could have html tags we don't want
        $validated['abstract'] = strip_tags($validated['abstract']);

        if ($this->useOllama) {
            return $this->queryOllama($validated['abstract']);
        } else {
            return $this->queryOpenAi($validated['abstract']);
        }
    }

    /**
     * Query the OpenAI API to generate a PLS.
     */
    private function queryOpenAi(string $abstract): JsonResponse
    {

        $result = OpenAILaravel::completions()->create($this->buildOpenAiPrompt($abstract));

        if (isset($result['choices'][0]['text'])) {
            $pls = $result['choices'][0]['text'];

            return $this->respond($pls, $pls);
        }

        return $this->error();
    }

    /**
     * Query the a local Ollama API to generate a PLS.
     */
    private function queryOllama(string $abstract): JsonResponse
    {
        $connector = new OllamaConnector;
        $request = new CompletionRequest($this->buildOllamaRequest($abstract, 'English'));
        $response = $connector->send($request);

        if ($response->failed()) {
            Log::error('Ollama request failed', ['response' => $response]);

            return $this->error();
        }

        $completion = $request->createDtoFromResponse($response);
        $pls_en = $completion->response;

        $connector = new OllamaConnector;
        $request = new CompletionRequest($this->buildOllamaTranslateRequest($pls_en));
        $response = $connector->send($request);

        if ($response->failed()) {
            Log::error('Ollama request failed', ['response' => $response]);

            return $this->error();
        }

        $completion = $request->createDtoFromResponse($response);
        $pls_fr = $completion->response;

        return $this->respond($pls_en, $pls_fr);
    }

    private function buildOllamaRequest(string $abstract, string $locale): CompletionRequestData
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

    private function buildOllamaTranslateRequest(string $abstract): CompletionRequestData
    {
        $prompt = "Translate the following plain language summary to French. Do
        not include introductory phrases in either french or english, your
        output must contain only the exact translation from this text:
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
     * Build the OpenAI query options.
     *
     * @param  string  $abstract
     */
    private function buildOpenAiPrompt($abstract): array
    {
        $basePrompt = 'Give me a 150 to 250 word plain language summary in both
        english and french for this text. It is written in a way that is
        difficult for most people to understand. Summarize it so that someone
        with a high-school education can understand. Do not include any other
        text.';

        $prompt = $basePrompt."\n\n Text: ###\n".$abstract."\n###";
        $model = 'gpt-3.5-turbo-instruct';

        return [
            'model' => $model,
            'prompt' => $prompt,
            'temperature' => 0.3,
            'max_tokens' => 500,
        ];
    }

    /**
     * Respond with the PLS.
     */
    private function respond(string $pls_en, string $pls_fr): JsonResponse
    {
        return response()->json([
            'data' => [
                'pls_en' => $pls_en,
                'pls_fr' => $pls_fr,
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
