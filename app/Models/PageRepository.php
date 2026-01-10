<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Db;
use App\Core\I18n;
use App\Core\Visitor;
use PDO;

final class PageRepository
{
    public static function getBySlug(string $slug, ?string $lang = null): ?array
    {
        $lang = $lang ? I18n::normalize($lang) : Visitor::language();

        $pdo = Db::pdo();
        $stmt = $pdo->prepare(
            'SELECT p.slug, t.lang, t.title, t.meta_description, t.body_html '
            . 'FROM pages p '
            . 'JOIN page_translations t ON t.page_id = p.id '
            . 'WHERE p.slug = :slug AND t.lang = :lang '
            . 'LIMIT 1'
        );
        $stmt->execute([':slug' => $slug, ':lang' => $lang]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }

        if ($lang !== I18n::default()) {
            return self::getBySlug($slug, I18n::default());
        }

        return null;
    }

    public static function getManyBySlug(array $slugs, ?string $lang = null): array
    {
        $lang = $lang ? I18n::normalize($lang) : Visitor::language();
        $out = [];
        foreach ($slugs as $slug) {
            $slug = (string)$slug;
            $page = self::getBySlug($slug, $lang);
            if ($page) {
                $out[$slug] = $page;
            }
        }

        return $out;
    }
}
