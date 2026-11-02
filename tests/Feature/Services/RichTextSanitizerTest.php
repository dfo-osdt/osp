<?php

declare(strict_types=1);

use App\Services\RichTextSanitizer;

test('sanitizing rich text keeps the editor formatting', function (): void {
    $html = '<div><b>Bold</b> <i>italic</i> <u>under</u> x<sup>2</sup> H<sub>2</sub>O <code>code</code></div><ul><li>one</li></ul><ol><li>two</li></ol><blockquote>quote</blockquote><p>para<br /></p>';

    expect(RichTextSanitizer::sanitize($html))->toBe($html);
});

test('sanitizing rich text removes anything that could run or load content', function (string $input, string $expected): void {
    expect(RichTextSanitizer::sanitize($input))->toBe($expected);
})->with([
    'script with its content' => ['Text<script>alert(1)</script> more', 'Text more'],
    'style with its content' => ['<style>body{display:none}</style>Text', 'Text'],
    'event handler attributes' => ['<b onclick="alert(1)">Bold</b>', '<b>Bold</b>'],
    'images' => ['Text<img src=x onerror="alert(1)">', 'Text'],
    'iframes' => ['<iframe src="https://example.com"></iframe>Text', 'Text'],
    'javascript links' => ['<a href="javascript:alert(1)">link</a>', '<a rel="noopener noreferrer">link</a>'],
    'style attributes' => ['<i style="position:fixed">italic</i>', '<i>italic</i>'],
    'unknown elements with their content' => ['<svg><text>x</text></svg>Text<title>t</title>', 'Text'],
]);

test('sanitizing rich text keeps web and mail links', function (): void {
    expect(RichTextSanitizer::sanitize('<a href="https://dfo-mpo.gc.ca" target="_blank">DFO</a> <a href="mailto:a@b.ca">mail</a>'))
        ->toBe('<a href="https://dfo-mpo.gc.ca" rel="noopener noreferrer">DFO</a> <a href="mailto:a&#64;b.ca" rel="noopener noreferrer">mail</a>');
});

test('sanitizing rich text unwraps unknown elements but keeps their text', function (): void {
    expect(RichTextSanitizer::sanitize('<span style="color:red">Red</span> <font>text</font>'))->toBe('Red text');
});

test('sanitizing rich text does not truncate long abstracts', function (): void {
    $html = '<p>'.str_repeat('a', 30_000).'</p>';

    expect(RichTextSanitizer::sanitize($html))->toBe($html);
});

test('sanitizing already sanitized rich text leaves it unchanged', function (): void {
    $sanitized = RichTextSanitizer::sanitize('<b>R&D</b> at a@b.ca: x = "y" + 1 <a href="https://x.ca">x</a>');

    expect(RichTextSanitizer::sanitize($sanitized))->toBe($sanitized);
});

test('plain rich text removes tags and decodes entities', function (): void {
    expect(RichTextSanitizer::toPlain(RichTextSanitizer::sanitize('<b>R&D</b> at a@b.ca: x = "y"')))
        ->toBe('R&D at a@b.ca: x = "y"');
});
