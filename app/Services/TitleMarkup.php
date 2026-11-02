<?php

namespace App\Services;

use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\Text;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

/**
 * Formatted titles are stored as HTML limited to <i>, <sup> and <sub>,
 * the subset shared by Crossref, PubMed, CSL and Zotero.
 */
class TitleMarkup
{
    /** Tags allowed in a stored title, in the order they nest (outermost first). */
    public const array TAGS = ['i', 'sup', 'sub'];

    public const int MAX_LENGTH = 1000;

    /** Elements whose content is never part of a title. */
    private const array DROPPED_ELEMENTS = ['script', 'style', 'template', 'noscript', 'head', 'title'];

    /** Elements that separate words, so their boundary becomes a space. */
    private const array BREAKING_ELEMENTS = ['br', 'p', 'div', 'li', 'ul', 'ol', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'tr', 'td'];

    /**
     * Normalize HTML into the canonical stored form without changing what the title says:
     * equivalent markup (<em>, styled spans) becomes <i>/<sup>/<sub>, other tags are unwrapped,
     * whitespace and &nbsp; collapse to single spaces, and text is escaped so that only
     * &amp;, &lt; and &gt; remain as entities.
     */
    public static function sanitize(string $html): string
    {
        $document = HTMLDocument::createFromString('<!DOCTYPE html><body>'.$html.'</body>', LIBXML_NOERROR);

        /** @var list<array{text: string, tags: list<string>}> $runs */
        $runs = [];
        self::collectRuns($document->body, [], $runs);

        return self::serializeRuns(self::trimRuns($runs));
    }

    /**
     * The title as plain text, for outputs that cannot carry formatting.
     */
    public static function toPlain(?string $html): string
    {
        return html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Split a stored title into a sequence of open tags, close tags and decoded text.
     *
     * @return list<array{type: 'open'|'close', tag: string}|array{type: 'text', text: string}>
     */
    public static function tokens(?string $html): array
    {
        $parts = preg_split('#(</?(?:i|sup|sub)>)#', (string) $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return array_map(function (string $part): array {
            if (preg_match('#^<(/?)(i|sup|sub)>$#', $part, $match)) {
                return ['type' => $match[1] === '' ? 'open' : 'close', 'tag' => $match[2]];
            }

            return ['type' => 'text', 'text' => html_entity_decode($part, ENT_QUOTES | ENT_HTML5, 'UTF-8')];
        }, $parts);
    }

    /**
     * The title as a spreadsheet rich text value, keeping italic, superscript and subscript.
     */
    public static function toRichText(?string $html): RichText
    {
        $richText = new RichText;
        $open = [];
        foreach (self::tokens($html) as $token) {
            if ($token['type'] === 'open') {
                $open[] = $token['tag'];
            } elseif ($token['type'] === 'close') {
                array_pop($open);
            } else {
                $font = $richText->createTextRun($token['text'])->getFont();
                $font?->setItalic(in_array('i', $open, true));
                if (in_array('sup', $open, true)) {
                    $font?->setSuperscript(true);
                } elseif (in_array('sub', $open, true)) {
                    $font?->setSubscript(true);
                }
            }
        }

        return $richText;
    }

    /**
     * The title tag an element stands for, if any (e.g. <em> or a span styled italic is <i>).
     */
    public static function formatOf(string $tagName, string $style = ''): ?string
    {
        $tagName = strtolower($tagName);
        if (in_array($tagName, self::TAGS, true)) {
            return $tagName;
        }
        if ($tagName === 'em') {
            return 'i';
        }
        if ($tagName === 'span') {
            $style = strtolower($style);
            if (preg_match('/font-style\s*:\s*(italic|oblique)/', $style)) {
                return 'i';
            }
            if (preg_match('/vertical-align\s*:\s*(super|sub)\b/', $style, $match)) {
                return $match[1] === 'super' ? 'sup' : 'sub';
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $tags
     * @param  list<array{text: string, tags: list<string>}>  $runs
     */
    private static function collectRuns(Node $node, array $tags, array &$runs): void
    {
        foreach ($node->childNodes as $child) {
            if ($child instanceof Text) {
                $runs[] = ['text' => $child->data, 'tags' => $tags];

                continue;
            }
            if (! $child instanceof Element) {
                continue;
            }

            $tagName = strtolower($child->localName);
            if (in_array($tagName, self::DROPPED_ELEMENTS, true)) {
                continue;
            }

            $childTags = $tags;
            $format = self::formatOf($tagName, (string) $child->getAttribute('style'));
            if ($format !== null && ! in_array($format, $childTags, true)) {
                $childTags[] = $format;
            }

            $isBreaking = in_array($tagName, self::BREAKING_ELEMENTS, true);
            if ($isBreaking) {
                $runs[] = ['text' => ' ', 'tags' => $tags];
            }
            self::collectRuns($child, $childTags, $runs);
            if ($isBreaking) {
                $runs[] = ['text' => ' ', 'tags' => $tags];
            }
        }
    }

    /**
     * Collapse whitespace, trim the title and drop empty runs.
     *
     * @param  list<array{text: string, tags: list<string>}>  $runs
     * @return list<array{text: string, tags: list<string>}>
     */
    private static function trimRuns(array $runs): array
    {
        $result = [];
        $previousEndsWithSpace = true;
        foreach ($runs as $run) {
            $text = preg_replace('/[\s\x{00A0}]+/u', ' ', $run['text']);
            if ($previousEndsWithSpace) {
                $text = ltrim($text, ' ');
            }
            if ($text === '') {
                continue;
            }
            $previousEndsWithSpace = str_ends_with($text, ' ');
            $result[] = ['text' => $text, 'tags' => $run['tags']];
        }

        $last = array_key_last($result);
        if ($last !== null) {
            $result[$last]['text'] = rtrim($result[$last]['text'], ' ');
            if ($result[$last]['text'] === '') {
                array_pop($result);
            }
        }

        return $result;
    }

    /**
     * Write runs as well-nested HTML, opening tags in canonical order and
     * reusing open tags across adjacent runs.
     *
     * @param  list<array{text: string, tags: list<string>}>  $runs
     */
    private static function serializeRuns(array $runs): string
    {
        $html = '';
        $open = [];
        foreach ($runs as $run) {
            $wanted = array_values(array_intersect(self::TAGS, $run['tags']));

            $keep = 0;
            while ($keep < count($open) && $keep < count($wanted) && $open[$keep] === $wanted[$keep]) {
                $keep++;
            }
            while (count($open) > $keep) {
                $html .= '</'.array_pop($open).'>';
            }
            foreach (array_slice($wanted, $keep) as $tag) {
                $html .= '<'.$tag.'>';
                $open[] = $tag;
            }

            $html .= htmlspecialchars($run['text'], ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        }
        while ($open !== []) {
            $html .= '</'.array_pop($open).'>';
        }

        return $html;
    }
}
