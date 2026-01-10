<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, array $handler): void
    {
        $method = strtoupper($method);
        $path = '/' . trim($path, '/');
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo View::render('errors/404', [
                'title' => '404',
            ]);
            return;
        }

        [$class, $action] = $handler;
        if (!class_exists($class)) {
            throw new RuntimeException('Controller not found: ' . $class);
        }
        $controller = new $class();
        if (!method_exists($controller, $action)) {
            throw new RuntimeException('Action not found: ' . $class . '::' . $action);
        }

        $result = $controller->{$action}();
        if (is_string($result)) {
            echo $result;
        }
    }
}
