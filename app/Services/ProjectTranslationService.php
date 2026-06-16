<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ProjectTranslationService
{
    public function translateToEnglish(?string $text): ?string
    {
        $text = trim((string) $text);

        if ($text === '' || env('PROJECT_TRANSLATION_ENABLED', false) != true) {
            return null;
        }

        return match (env('PROJECT_TRANSLATION_PROVIDER', 'google')) {
            'google' => $this->translateWithGoogle($text),
            default => null,
        };
    }

    public function translateHtmlToEnglish(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '' || env('PROJECT_TRANSLATION_ENABLED', false) != true) {
            return null;
        }

        $originalUseErrors = libxml_use_internal_errors(true);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<div id="project-translation-root">' . mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') . '</div>';

        if (!$document->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            libxml_clear_errors();
            libxml_use_internal_errors($originalUseErrors);

            return $this->translateToEnglish($html);
        }

        $this->translateTextNodes($document);

        $root = $document->getElementById('project-translation-root');
        $translated = '';

        if ($root) {
            foreach ($root->childNodes as $child) {
                $translated .= $document->saveHTML($child);
            }
        }

        libxml_clear_errors();
        libxml_use_internal_errors($originalUseErrors);

        return trim($translated) !== '' ? trim($translated) : null;
    }

    private function translateTextNodes(\DOMNode $node): void
    {
        if ($node instanceof \DOMText) {
            $text = trim($node->nodeValue);

            if ($text !== '') {
                $translated = $this->translateToEnglish($text);

                if ($translated) {
                    $node->nodeValue = $translated;
                }
            }

            return;
        }

        if (in_array(strtolower($node->nodeName), ['script', 'style', 'code', 'pre'], true)) {
            return;
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            $this->translateTextNodes($child);
        }
    }

    private function translateWithGoogle(string $text): ?string
    {
        $baseUrl = rtrim((string) env('GOOGLE_TRANSLATE_URL', 'https://translate.googleapis.com/translate_a/single'), '/');

        try {
            $response = Http::timeout(12)->get($baseUrl, [
                'client' => 'gtx',
                'sl' => 'pt',
                'tl' => 'en',
                'dt' => 't',
                'q' => $text,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $payload = $response->json();
            $segments = is_array($payload) ? ($payload[0] ?? []) : [];

            $translated = collect($segments)
                ->pluck('0')
                ->filter()
                ->implode('');

            return $translated !== '' ? $translated : null;
        } catch (Throwable) {
            return null;
        }
    }
}
