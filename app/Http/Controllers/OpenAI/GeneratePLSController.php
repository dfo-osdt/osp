<?php

namespace App\Http\Controllers\OpenAI;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * This controller will use the the OpenAI API to summarize scientific
 * abstracts into a plain language summary (PLS.)
 */
class GeneratePLSController extends Controller
{
    // When working on this prompt refer to this article:
    // https://help.openai.com/en/articles/6654000-best-practices-for-prompt-engineering-with-openai-api

    private static string $basePrompt = 'The text below is a scientific paper abstract. It is written in a way that is difficult for most people to understand. Summarize it so that an 8th grader can understand. If the abstract is in french, summarize it in french. If it is in english, summarize it in english.';

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

        $result = OpenAI::completions()->create($this->buildOpenAiPrompt($validated['abstract']));

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

        return [
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 500,
        ];
    }

    private function error($message = 'Feature temporarily unavailable.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }
}
