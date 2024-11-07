<?php

namespace App\Http\Controllers\OpenAI;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    private ?string $ollamaUrl = null;

    private ?string $ollamaModel = null;

    public function __construct()
    {
        $this->useOllama = config('openai.use_ollama');
        $this->ollamaUrl = config('openai.ollama_url');
        $this->ollamaModel = config('openai.ollama_model');
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

        if (! $this->useOllama) {
            $result = OpenAILaravel::completions()->create($this->buildOpenAiPrompt($validated['abstract']));
        } else {
            $client = OpenAI::factory()->withApiKey('ollama')->withBaseUri($this->ollamaUrl)->make();
            $result = $client->completions()->create($this->buildOpenAiPrompt($validated['abstract']));
        }
        // does result contain an error?
        if (isset($result['error'])) {
            return $this->error();
        }

        $pls = $result['choices'][0]['text'];
        // clean up the PLS - remove newlines and extra spaces
        $pls = str_replace("\n", ' ', $pls);

        // build the response
        $response = [
            'data' => [
                'pls' => $pls,
            ],
        ];

        return response()->json($response);
    }

    /**
     * Build the OpenAI query options.
     *
     * @param  string  $abstract
     */
    private function buildOpenAiPrompt($abstract): array
    {
        $prompt = self::$basePrompt."\n\n Text: ###\n".$abstract."\n###";
        if ($this->useOllama) {
            $prompt = "Give me a 150 to 250 word plain language summary for this text. It is written in a way that is difficult for most people to understand. Summarize it so that someone with a high-school education can understand. Please respond only with the answer, without any introductory or concluding phrases\n\n Text: ###\n".$abstract."\n###";
        }
        $model = $this->useOllama ? $this->ollamaModel : 'gpt-3.5-turbo-instruct';

        $request = [
            'model' => $model,
            'prompt' => $prompt,
            'temperature' => 0.3,
        ];

        if (! $this->useOllama) {
            $request['max_tokens'] = 500;
        }

        return $request;

    }

    private function error($message = 'Feature temporarily unavailable.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }
}
