<?php

declare(strict_types=1);

namespace App\Core;

final class I18n
{
    public static function default(): string
    {
        return (string)App::config('i18n.default', 'en');
    }

    public static function supported(): array
    {
        $supported = App::config('i18n.supported', ['en']);
        return is_array($supported) ? $supported : ['en'];
    }

    public static function isSupported(string $lang): bool
    {
        return in_array($lang, self::supported(), true);
    }

    public static function normalize(string $lang): string
    {
        $lang = strtolower(trim($lang));
        if ($lang === '') {
            return self::default();
        }

        if (str_contains($lang, '-')) {
            $lang = explode('-', $lang, 2)[0];
        }

        return $lang;
    }

    public static function detectLanguage(): string
    {
        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        if (!is_string($header) || $header === '') {
            return self::default();
        }

        $candidates = [];
        foreach (explode(',', $header) as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $sub = explode(';q=', $part, 2);
            $lang = self::normalize($sub[0]);
            $q = isset($sub[1]) ? (float)$sub[1] : 1.0;
            $candidates[] = ['lang' => $lang, 'q' => $q];
        }

        usort($candidates, static fn($a, $b) => $b['q'] <=> $a['q']);

        foreach ($candidates as $candidate) {
            if (self::isSupported($candidate['lang'])) {
                return $candidate['lang'];
            }
        }

        return self::default();
    }
}
