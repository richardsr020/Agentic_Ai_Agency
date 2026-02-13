<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;

final class AuthController
{
    public function showLogin(): string
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        return View::render('auth/login', [
            'page' => ['title' => 'Connexion'],
        ]);
    }

    public function showRegister(): string
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        return View::render('auth/register', [
            'page' => ['title' => 'Inscription'],
        ]);
    }

    public function register(): void
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input') ?: '{}', true);

            $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
            if ($email) {
                $email = strtolower((string)$email);
            }
            $password = $data['password'] ?? '';
            $name = trim($data['name'] ?? '');

            if (!$email) {
                http_response_code(400);
                echo json_encode(['error' => 'Email invalide']);
                return;
            }

            if (strlen($password) < 8) {
                http_response_code(400);
                echo json_encode(['error' => 'Le mot de passe doit contenir au moins 8 caractères']);
                return;
            }

            if (strlen($name) < 2) {
                http_response_code(400);
                echo json_encode(['error' => 'Le nom doit contenir au moins 2 caractères']);
                return;
            }

            $user = Auth::register($email, $password, $name);
            Auth::login($user['id'], true);

            echo json_encode(['success' => true, 'user' => $this->sanitizeUser($user)]);
        } catch (\Throwable $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login(): void
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input') ?: '{}', true);

            $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = $data['password'] ?? '';

            if (!$email || !$password) {
                http_response_code(400);
                echo json_encode(['error' => 'Email et mot de passe requis']);
                return;
            }

            $user = Auth::attempt($email, $password);
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Email ou mot de passe incorrect']);
                return;
            }

            Auth::login($user['id'], true);

            echo json_encode(['success' => true, 'user' => $this->sanitizeUser($user)]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function emailLogin(): void
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input') ?: '{}', true);
            $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

            if (!$email) {
                http_response_code(400);
                echo json_encode(['error' => 'Email invalide']);
                return;
            }

            $user = Auth::loginEmailOnly($email);
            echo json_encode(['success' => true, 'user' => $this->sanitizeUser($user)]);
        } catch (\RuntimeException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function user(): void
    {
        header('Content-Type: application/json');
        $user = Auth::user();
        if ($user) {
            echo json_encode(['user' => $this->sanitizeUser($user)]);
        } else {
            echo json_encode(['user' => null]);
        }
    }

    public function googleCallback(): void
    {
        // This will be implemented with Google OAuth
        // For now, just redirect
        header('Location: /auth/google');
    }

    private function sanitizeUser(array $user): array
    {
        unset($user['password_hash']);
        return $user;
    }
}
