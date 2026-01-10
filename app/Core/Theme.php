<?php

declare(strict_types=1);

namespace App\Core;

final class Theme
{
    public static function default(): string
    {
        return (string)App::config('theme.default', 'light');
    }

    public static function supported(): array
    {
        $supported = App::config('theme.supported', ['light', 'dark']);
        return is_array($supported) ? $supported : ['light', 'dark'];
    }

    public static function isSupported(string $theme): bool
    {
        return in_array($theme, self::supported(), true);
    }

    public static function normalize(string $theme): string
    {
        $theme = strtolower(trim($theme));
        return $theme === '' ? self::default() : $theme;
    }
}
