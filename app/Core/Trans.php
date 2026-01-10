<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Trans
{
    public static function get(string $namespace, string $key, ?string $lang = null): string
    {
        $lang = $lang ? I18n::normalize($lang) : Visitor::language();

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('SELECT text FROM ui_translations WHERE namespace = :ns AND translation_key = :k AND lang = :lang LIMIT 1');
        $stmt->execute([
            ':ns' => $namespace,
            ':k' => $key,
            ':lang' => $lang,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['text'])) {
            return (string)$row['text'];
        }

        if ($lang !== I18n::default()) {
            return self::get($namespace, $key, I18n::default());
        }

        return $namespace . '.' . $key;
    }
}
