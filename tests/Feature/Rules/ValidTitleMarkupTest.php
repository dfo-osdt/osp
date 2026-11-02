<?php

use App\Rules\ValidTitleMarkup;
use Illuminate\Support\Facades\Validator;

function validateTitle(mixed $title): Illuminate\Validation\Validator
{
    return Validator::make(['title' => $title], ['title' => new ValidTitleMarkup]);
}

test('titles with supported or equivalent markup pass validation', function (string $title): void {
    expect(validateTitle($title)->passes())->toBeTrue();
})->with([
    'plain text' => 'Growth of juvenile salmon',
    'italic' => 'Growth of <i>Salmo salar</i> juveniles',
    'superscript and subscript' => 'CO<sub>2</sub> uptake at 10<sup>-3</sup> M',
    'nested formats' => '<i>x<sub>2</sub></i> and <sup><i>n</i></sup>',
    'em from a browser' => 'Growth of <em>Salmo salar</em>',
    'italic span from a browser' => 'Growth of <span style="font-style: italic;">Salmo salar</span>',
    'superscript span from a browser' => '10<span style="vertical-align: super;">3</span>',
    'plain span and line break' => '<span>Growth</span> of salmon<br>',
    'escaped angle brackets' => 'Effect at p &lt; 0.05 &amp; more',
    'bare less-than sign' => 'Effect at p < 0.05',
    '1000 characters of text' => '<i>'.str_repeat('a', 1000).'</i>',
]);

test('titles with markup the sanitizer would have to discard fail with a message', function (string $title, string $message): void {
    $validator = validateTitle($title);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('title'))->toBe($message);
})->with([
    'bold' => ['Growth of <b>salmon</b>', 'The title may only use italic, superscript and subscript formatting.'],
    'underline' => ['Growth of <u>salmon</u>', 'The title may only use italic, superscript and subscript formatting.'],
    'link' => ['<a href="https://example.com">Growth</a>', 'The title may only use italic, superscript and subscript formatting.'],
    'script' => ['Growth<script>alert(1)</script>', 'The title may only use italic, superscript and subscript formatting.'],
    'unclosed italic' => ['<i>Salmo salar', 'The title has an unclosed <i> tag.'],
    'stray closing tag' => ['Salmo salar</i>', 'The title has a misplaced closing </i> tag.'],
    'misnested tags' => ['<i>x<sub>2</i></sub>', 'The title has a misplaced closing </i> tag.'],
    'italic inside italic' => ['<i>Salmo <i>salar</i></i>', 'The title cannot nest the same formatting inside itself.'],
    'em inside italic' => ['<i>Salmo <em>salar</em></i>', 'The title cannot nest the same formatting inside itself.'],
    'tags without text' => ['<i> </i>', 'The title must contain text.'],
    'over 1000 characters of text' => [str_repeat('a', 1001), 'The title may not be greater than 1000 characters.'],
]);

test('non-string titles fail validation', function (): void {
    $validator = validateTitle(['<i>Salmo</i>']);

    expect($validator->errors()->first('title'))->toBe('The title must be a string.');
});
