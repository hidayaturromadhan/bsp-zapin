<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAutoTranslator
{
    public function translateText(?string $text, string $source = 'id', string $target = 'en'): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        $apiKey = config('services.deepl.key');
        $url = config('services.deepl.url');

        if (! $apiKey || ! $url) {
            Log::warning('DeepL API not configured');
            return $text;
        }

        try {
            $response = Http::timeout(30)
                ->retry(2, 500)
                ->withHeaders([
                    'Authorization' => 'DeepL-Auth-Key ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'text' => [$text],
                    'source_lang' => strtoupper($source), // ID
                    'target_lang' => strtoupper($target), // EN
                ]);

            if ($response->successful()) {
                return trim((string) data_get($response->json(), 'translations.0.text', $text)) ?: $text;
            }

            Log::warning('DeepL translate failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('DeepL translate exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return $text;
    }

    public function translateHtml(?string $html, string $source = 'id', string $target = 'en'): string
    {
        return $this->translateText($html, $source, $target);
    }

    public function translateBlocks(array $blocks, string $source = 'id', string $target = 'en'): array
    {
        $translated = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? 'text';

            if ($type === 'heading') {
                $translated[] = [
                    'type' => 'heading',
                    'title' => $this->translateText($block['title'] ?? '', $source, $target),
                ];
                continue;
            }

            if ($type === 'text') {
                $translated[] = [
                    'type' => 'text',
                    'body' => $this->translateText($block['body'] ?? '', $source, $target),
                ];
                continue;
            }

            if ($type === 'image') {
                $translated[] = [
                    'type' => 'image',
                    'image' => $block['image'] ?? null,
                    'caption' => $this->translateText($block['caption'] ?? '', $source, $target),
                ];
                continue;
            }

            $translated[] = $block;
        }

        return $translated;
    }
}