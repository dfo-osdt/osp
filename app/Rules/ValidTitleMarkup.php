<?php

namespace App\Rules;

use App\Services\TitleMarkup;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Reject title markup the sanitizer would have to change the meaning of: unsupported tags,
 * unclosed or misnested tags, formatting nested inside itself, and titles with no text.
 * Equivalent markup produced by browsers (<em>, styled spans, <br>) is accepted.
 */
class ValidTitleMarkup implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        preg_match_all('#<(/?)([a-zA-Z][a-zA-Z0-9]*)([^>]*)>#', $value, $matches, PREG_SET_ORDER);

        /** @var list<array{tag: string, format: ?string}> $stack */
        $stack = [];
        foreach ($matches as [, $closing, $tagName, $attributes]) {
            $tagName = strtolower($tagName);

            if ($tagName === 'br') {
                continue;
            }

            if ($tagName !== 'span' && TitleMarkup::formatOf($tagName) === null) {
                $fail('The :attribute may only use italic, superscript and subscript formatting.');

                return;
            }

            if ($closing === '/') {
                $opened = array_pop($stack);
                if ($opened === null || $opened['tag'] !== $tagName) {
                    $fail("The :attribute has a misplaced closing </{$tagName}> tag.");

                    return;
                }

                continue;
            }

            $style = preg_match('/style\s*=\s*(["\'])(.*?)\1/i', $attributes, $styleMatch) ? $styleMatch[2] : '';
            $format = TitleMarkup::formatOf($tagName, $style);
            if ($format !== null && in_array($format, array_column($stack, 'format'), true)) {
                $fail('The :attribute cannot nest the same formatting inside itself.');

                return;
            }
            $stack[] = ['tag' => $tagName, 'format' => $format];
        }

        if ($stack !== []) {
            $fail('The :attribute has an unclosed <'.end($stack)['tag'].'> tag.');

            return;
        }

        $plain = trim(TitleMarkup::toPlain($value));
        if ($plain === '') {
            $fail('The :attribute must contain text.');

            return;
        }

        if (mb_strlen($plain) > TitleMarkup::MAX_LENGTH) {
            $fail('The :attribute may not be greater than '.TitleMarkup::MAX_LENGTH.' characters.');
        }
    }
}
