<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../Views/' . trim($view, '/') . '.php';
        if (!is_file($viewPath)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        $layoutPath = __DIR__ . '/../Views/layout.php';
        if (!is_file($layoutPath)) {
            throw new RuntimeException('Layout not found');
        }

        $content = self::capture($viewPath, $data);

        return self::capture($layoutPath, array_merge($data, [
            'content' => $content,
        ]));
    }

    public static function renderContent(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../Views/' . trim($view, '/') . '.php';
        if (!is_file($viewPath)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        return self::capture($viewPath, $data);
    }

    public static function partial(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../Views/' . trim($view, '/') . '.php';
        if (!is_file($viewPath)) {
            throw new RuntimeException('Partial not found: ' . $view);
        }

        return self::capture($viewPath, $data);
    }

    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string)ob_get_clean();
    }
}
