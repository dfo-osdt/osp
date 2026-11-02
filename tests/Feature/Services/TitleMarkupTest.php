<?php

use App\Services\TitleMarkup;
use PhpOffice\PhpSpreadsheet\RichText\ITextElement;

test('sanitizing a title normalizes markup without changing its text', function (string $input, string $expected): void {
    expect(TitleMarkup::sanitize($input))->toBe($expected);
})->with([
    'canonical markup is unchanged' => ['Growth of <i>Salmo salar</i> and CO<sub>2</sub> at 10<sup>-3</sup>', 'Growth of <i>Salmo salar</i> and CO<sub>2</sub> at 10<sup>-3</sup>'],
    'em becomes italic' => ['<em>Salmo salar</em>', '<i>Salmo salar</i>'],
    'styled spans become tags' => ['<span style="font-style:italic">Salmo</span> 10<span style="vertical-align: sub">2</span>', '<i>Salmo</i> 10<sub>2</sub>'],
    'other tags are unwrapped' => ['<b>Growth</b> of <a href="x">salmon</a>', 'Growth of salmon'],
    'script content is dropped' => ['Growth<script>alert(1)</script> of salmon', 'Growth of salmon'],
    'line breaks become spaces' => ['Growth<br>of<div>salmon</div>', 'Growth of salmon'],
    'whitespace and nbsp collapse and trim' => ["  Growth&nbsp;&nbsp;of \n salmon  ", 'Growth of salmon'],
    'italic inside italic collapses' => ['<i>Salmo <i>salar</i></i>', '<i>Salmo salar</i>'],
    'adjacent italics merge' => ['<i>Salmo</i><i> salar</i>', '<i>Salmo salar</i>'],
    'nesting is written in canonical order' => ['<sub><i>x</i></sub>', '<i><sub>x</sub></i>'],
    'empty tags are removed' => ['Growth <i></i>of salmon', 'Growth of salmon'],
    'unclosed tags are closed at the end' => ['<i>Salmo salar', '<i>Salmo salar</i>'],
    'plain text is escaped' => ['Fish & Wildlife: p < 0.05', 'Fish &amp; Wildlife: p &lt; 0.05'],
    'other entities are decoded' => ['Caf&eacute; &quot;study&quot; &#8211; part&nbsp;1', 'Café "study" – part 1'],
    'typed tags stay literal text' => ['&lt;i&gt;Salmo&lt;/i&gt;', '&lt;i&gt;Salmo&lt;/i&gt;'],
]);

test('sanitizing an already sanitized title leaves it unchanged', function (): void {
    $sanitized = TitleMarkup::sanitize('<em>Salmo</em> &amp; CO<span style="vertical-align:sub">2</span> &lt; 5');

    expect(TitleMarkup::sanitize($sanitized))->toBe($sanitized);
});

test('the plain title removes tags and decodes entities', function (): void {
    expect(TitleMarkup::toPlain('<i>Salmo salar</i> &amp; CO<sub>2</sub> at p &lt; 0.05'))
        ->toBe('Salmo salar & CO2 at p < 0.05');
});

test('the rich text title keeps italic, superscript and subscript runs', function (): void {
    $runs = TitleMarkup::toRichText('Growth of <i>Salmo<sub>2</sub></i> &amp; 10<sup>3</sup>')->getRichTextElements();

    expect(array_map(fn (ITextElement $run): array => [
        $run->getText(),
        $run->getFont()->getItalic(),
        $run->getFont()->getSubscript(),
        $run->getFont()->getSuperscript(),
    ], $runs))->toBe([
        ['Growth of ', false, false, false],
        ['Salmo', true, false, false],
        ['2', true, true, false],
        [' & 10', false, false, false],
        ['3', false, false, true],
    ]);
});
