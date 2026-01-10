<?php

declare(strict_types=1);

require __DIR__ . '/Core/Support.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

App\Core\App::setConfig(require __DIR__ . '/../config/config.php');

App\Core\Db::init();
App\Core\Visitor::boot();
