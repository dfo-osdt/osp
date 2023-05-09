<?php

namespace App\Http\Controllers\OpenAI;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;

/**
 * This controller will use the the OpenAI API to summarize scientific
 * abstracts into a plain language summary (PLS.)
 */
class GeneratePLSController extends Controller
{
    private static $basePrompt = "This is a scientific abstract. It is written in a way that is difficult for most people to understand. Summarize it so that an 8th grader can understand. Here is the abstract: \n\n";

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'abstract' => 'required|string|max:2000',
        ]);

        // clean up the abstract - it could have html tags we don't want
        $validated['abstract'] = strip_tags($validated['abstract']);

        // if API key is not set, return an error
        if (config('osp.openai_api_key') === null) {
            return $this->error();
        }

        $openAi = new OpenAi(config('osp.openai_api_key'));
        $result = $openAi->completion($this->buildOpenAiPrompt($validated['abstract']));

        // does result contain an error?
        if (isset($result['error'])) {
            return $this->error();
        }

        // take results, from json to an array
        $result = json_decode($result, true);

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
        $prompt = self::$basePrompt.$abstract;

        return [
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'temperature' => 0.7,
            'max_tokens' => 256,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ];
    }

    private function error($message = 'Feature temporarily unavailable.'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }
}
