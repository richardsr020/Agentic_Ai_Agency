<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Visitor
{
    private static ?array $current = null;

    public static function boot(): void
    {
        $cookieName = (string)App::config('app.cookie.visitor_id', 'visitor_id');
        $visitorId = $_COOKIE[$cookieName] ?? null;

        if (!is_string($visitorId) || $visitorId === '') {
            $visitorId = self::uuid();
            self::create($visitorId);
            self::setCookie($cookieName, $visitorId);
        }

        $row = self::find($visitorId);
        if (!$row) {
            self::create($visitorId);
            $row = self::find($visitorId);
        }

        self::$current = $row;

        if (!self::$current['language']) {
            $lang = I18n::detectLanguage();
            self::setLanguage($lang);
        }

        if (!self::$current['theme']) {
            $theme = Theme::default();
            self::setTheme($theme);
        }
    }

    public static function current(): array
    {
        return self::$current ?? [];
    }

    public static function language(): string
    {
        $lang = self::$current['language'] ?? null;
        if (is_string($lang) && $lang !== '') {
            return $lang;
        }
        return I18n::default();
    }

    public static function theme(): string
    {
        $theme = self::$current['theme'] ?? null;
        if (is_string($theme) && $theme !== '') {
            return $theme;
        }
        return Theme::default();
    }

    public static function setLanguage(string $lang): void
    {
        $lang = I18n::normalize($lang);
        if (!I18n::isSupported($lang)) {
            $lang = I18n::default();
        }

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('UPDATE visitors SET language = :language, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            ':language' => $lang,
            ':updated_at' => gmdate('c'),
            ':id' => self::$current['id'],
        ]);

        self::$current['language'] = $lang;
    }

    public static function setTheme(string $theme): void
    {
        $theme = Theme::normalize($theme);
        if (!Theme::isSupported($theme)) {
            $theme = Theme::default();
        }

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('UPDATE visitors SET theme = :theme, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            ':theme' => $theme,
            ':updated_at' => gmdate('c'),
            ':id' => self::$current['id'],
        ]);

        self::$current['theme'] = $theme;
    }

    private static function find(string $id): ?array
    {
        $pdo = Db::pdo();
        $stmt = $pdo->prepare('SELECT id, language, theme, created_at, updated_at FROM visitors WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private static function create(string $id): void
    {
        $pdo = Db::pdo();
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO visitors (id, language, theme, created_at, updated_at) VALUES (:id, :language, :theme, :created_at, :updated_at)');
        $now = gmdate('c');
        $stmt->execute([
            ':id' => $id,
            ':language' => null,
            ':theme' => null,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
    }

    private static function setCookie(string $name, string $value): void
    {
        $days = (int)App::config('app.cookie.days', 365);
        $secure = (bool)App::config('app.cookie.secure', false);
        $httpOnly = (bool)App::config('app.cookie.http_only', true);
        $sameSite = (string)App::config('app.cookie.same_site', 'Lax');

        setcookie($name, $value, [
            'expires' => time() + ($days * 86400),
            'path' => '/',
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]);
    }

    private static function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        $hex = bin2hex($data);
        return sprintf('%s-%s-%s-%s-%s', substr($hex, 0, 8), substr($hex, 8, 4), substr($hex, 12, 4), substr($hex, 16, 4), substr($hex, 20));
    }
}
