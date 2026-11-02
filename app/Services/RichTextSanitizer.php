<?php

namespace App\Services;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Rich text (abstracts, plain language summaries, review comments) is stored as HTML
 * limited to what the SPA's QuestionEditor toolbar produces. Common text wrappers from
 * pasted content are unwrapped so their text is kept; any other element (scripts,
 * styles, embeds) is dropped with its content, Symfony's default.
 */
class RichTextSanitizer
{
    /** Elements the editor can produce, with the attributes each may keep. */
    private const array ALLOWED_ELEMENTS = [
        'b' => [], 'strong' => [], 'i' => [], 'em' => [], 'u' => [],
        'sup' => [], 'sub' => [], 'code' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [],
        'p' => [], 'div' => [], 'br' => [],
        'a' => ['href'],
    ];

    /** Wrappers removed while keeping their content. */
    private const array UNWRAPPED_ELEMENTS = [
        'span', 'font', 'small', 'big', 'mark', 's', 'strike', 'del', 'ins', 'cite', 'abbr', 'center', 'pre',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'section', 'article', 'header', 'footer', 'main', 'aside', 'nav',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'caption', 'dl', 'dt', 'dd', 'figure', 'figcaption', 'label',
    ];

    private static ?HtmlSanitizer $sanitizer = null;

    public static function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        return self::sanitizer()->sanitize($html);
    }

    /**
     * The rich text as plain text, for outputs that cannot carry markup.
     */
    public static function toPlain(?string $html): string
    {
        return html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private static function sanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer === null) {
            $config = (new HtmlSanitizerConfig)
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->allowRelativeLinks(false)
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->withMaxInputLength(-1);

            foreach (self::ALLOWED_ELEMENTS as $element => $attributes) {
                $config = $config->allowElement($element, $attributes);
            }
            foreach (self::UNWRAPPED_ELEMENTS as $element) {
                $config = $config->blockElement($element);
            }

            self::$sanitizer = new HtmlSanitizer($config);
        }

        return self::$sanitizer;
    }
}
