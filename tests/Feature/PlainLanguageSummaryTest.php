<?php

use App\Ai\Agents\PlainLanguageSummaryTranslator;
use App\Ai\Agents\PlainLanguageSummaryWriter;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Exceptions\ProviderConnectionException;
use Laravel\Ai\Prompts\AgentPrompt;

beforeEach(function (): void {
    config(['osp.ollama.enabled' => true]);
});

test('an unauthenticated user cannot use the plain language summary tools', function (string $url): void {
    $this->postJson($url, ['abstract' => 'An abstract.', 'locale' => 'en'])->assertUnauthorized();
})->with(['/api/utils/generate-pls', '/api/utils/translate-pls']);

test('the abstract and locale are required', function (string $url): void {
    $this->actingAs(User::factory()->create())
        ->postJson($url, [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['abstract', 'locale']);
})->with(['/api/utils/generate-pls', '/api/utils/translate-pls']);

test('the locale must be english or french', function (): void {
    $this->actingAs(User::factory()->create())
        ->postJson('/api/utils/generate-pls', ['abstract' => 'An abstract.', 'locale' => 'de'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['locale' => 'The selected locale is invalid.']);
});

test('a user can generate a plain language summary from the abstract text', function (): void {
    PlainLanguageSummaryWriter::fake(['Fish swim in the sea.'])->preventStrayPrompts();

    $response = $this->actingAs(User::factory()->create())->postJson('/api/utils/generate-pls', [
        'abstract' => '<p>Salmonids <strong>migrate</strong> &amp; spawn.</p>',
        'locale' => 'fr',
    ]);

    $response->assertOk()->assertExactJson(['data' => ['pls' => 'Fish swim in the sea.']]);
    PlainLanguageSummaryWriter::assertPrompted(fn (AgentPrompt $prompt): bool => $prompt->prompt === 'Salmonids migrate & spawn.'
        && $prompt->agent->language === 'French');
});

test('the configured Ollama server and model answer with our generation settings', function (string $url): void {
    config([
        'ai.providers.ollama.url' => 'http://ollama.test',
        'ai.providers.ollama.models.text.default' => 'llama3.3',
    ]);
    Http::preventStrayRequests();
    Http::fake(['http://ollama.test/api/chat' => Http::response([
        'model' => 'llama3.3',
        'message' => ['role' => 'assistant', 'content' => 'Fish swim in the sea.'],
        'done' => true,
        'done_reason' => 'stop',
    ])]);

    $response = $this->actingAs(User::factory()->create())->postJson($url, [
        'abstract' => 'Salmonids migrate.',
        'locale' => 'en',
    ]);

    $response->assertOk()->assertExactJson(['data' => ['pls' => 'Fish swim in the sea.']]);
    Http::assertSent(fn (Request $request): bool => $request['model'] === 'llama3.3'
        && $request['think'] === false
        && $request['options'] === ['temperature' => 0.3, 'num_ctx' => 4096, 'repeat_penalty' => 1.15]);
})->with(['/api/utils/generate-pls', '/api/utils/translate-pls']);

test('a user can translate a plain language summary', function (): void {
    PlainLanguageSummaryTranslator::fake(['Fish swim in the sea.'])->preventStrayPrompts();

    $response = $this->actingAs(User::factory()->create())->postJson('/api/utils/translate-pls', [
        'abstract' => '<p>Les poissons nagent dans la mer.</p>',
        'locale' => 'en',
    ]);

    $response->assertOk()->assertExactJson(['data' => ['pls' => 'Fish swim in the sea.']]);
    PlainLanguageSummaryTranslator::assertPrompted(fn (AgentPrompt $prompt): bool => $prompt->prompt === 'Les poissons nagent dans la mer.'
        && $prompt->agent->language === 'English');
});

test('the plain language summary tools are unavailable when AI is disabled', function (string $url, string $agent): void {
    config(['osp.ollama.enabled' => false]);
    $agent::fake();

    $this->actingAs(User::factory()->create())
        ->postJson($url, ['abstract' => 'An abstract.', 'locale' => 'en'])
        ->assertInternalServerError()
        ->assertExactJson(['message' => 'Feature temporarily unavailable.']);
    $agent::assertNeverPrompted();
})->with([
    'generate' => ['/api/utils/generate-pls', PlainLanguageSummaryWriter::class],
    'translate' => ['/api/utils/translate-pls', PlainLanguageSummaryTranslator::class],
]);

test('an empty answer tells the user the feature is unavailable', function (string $url, string $agent): void {
    $agent::fake(["  \n"]);

    $this->actingAs(User::factory()->create())
        ->postJson($url, ['abstract' => 'An abstract.', 'locale' => 'en'])
        ->assertInternalServerError()
        ->assertExactJson(['message' => 'Feature temporarily unavailable.']);
})->with([
    'generate' => ['/api/utils/generate-pls', PlainLanguageSummaryWriter::class],
    'translate' => ['/api/utils/translate-pls', PlainLanguageSummaryTranslator::class],
]);

test('a provider failure tells the user the feature is unavailable', function (string $url, string $agent): void {
    $agent::fake(fn (): never => throw ProviderConnectionException::forProvider('ollama'));

    $this->actingAs(User::factory()->create())
        ->postJson($url, ['abstract' => 'An abstract.', 'locale' => 'en'])
        ->assertInternalServerError()
        ->assertExactJson(['message' => 'Feature temporarily unavailable.']);
})->with([
    'generate' => ['/api/utils/generate-pls', PlainLanguageSummaryWriter::class],
    'translate' => ['/api/utils/translate-pls', PlainLanguageSummaryTranslator::class],
]);
