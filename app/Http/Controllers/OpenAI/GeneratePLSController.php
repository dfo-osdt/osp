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

    private static string $basePrompt = 'The text below is a scientific paper abstract. It is written in a way that is difficult for most people to understand. Summarize it so that an 8th grader can understand. If the abstract is in french, summarize it in french. If it is in english, summarize it in english.';

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

            return $this->respond($pls);
        }

        return $this->error();

    }

    /**
     * Query the a local Ollama API to generate a PLS.
     */
    private function queryOllama(string $abstract): JsonResponse
    {
        $connector = new OllamaConnector;
        $request = new CompletionRequest($this->buildOllamaRequest($abstract));
        $response = $connector->send($request);

        if ($response->failed()) {
            Log::error('Ollama request failed', ['response' => $response]);

            return $this->error();
        }

        $completion = $request->createDtoFromResponse($response);
        $pls = $completion->response;

        return $this->respond($pls);

    }

    private function buildOllamaRequest(string $abstract): CompletionRequestData
    {

        $prompt = "Give me a 150 to 250 word plain language summary for this text. It is written in a way that is difficult for most people to understand. Summarize it so that someone with a high-school education can understand. Please respond only with the answer, without any introductory or concluding phrases\n\n Text: ###\n".$abstract."\n###";

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
        $prompt = self::$basePrompt."\n\n Text: ###\n".$abstract."\n###";
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
    private function respond(string $pls): JsonResponse
    {
        // clean up the PLS - remove newlines and extra spaces
        $pls = str_replace("\n", ' ', $pls);

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
