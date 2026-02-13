<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

final class Auth
{
    private static ?array $currentUser = null;
    private const SESSION_COOKIE_NAME = 'session_token';
    private const SESSION_DURATION_DAYS = 30;

    public static function init(): void
    {
        $sessionToken = $_COOKIE[self::SESSION_COOKIE_NAME] ?? null;
        
        if ($sessionToken && is_string($sessionToken)) {
            self::$currentUser = self::validateSession($sessionToken);
        }
    }

    public static function user(): ?array
    {
        return self::$currentUser;
    }

    public static function check(): bool
    {
        return self::$currentUser !== null;
    }

    public static function id(): ?int
    {
        return self::$currentUser['id'] ?? null;
    }

    public static function login(int $userId, bool $remember = true): string
    {
        $pdo = Db::pdo();
        
        // Delete expired sessions for this user
        $pdo->prepare('DELETE FROM user_sessions WHERE user_id = :user_id AND expires_at < :now')
            ->execute([':user_id' => $userId, ':now' => gmdate('c')]);

        // Generate session token
        $sessionToken = bin2hex(random_bytes(32));
        $expiresAt = gmdate('c', time() + (self::SESSION_DURATION_DAYS * 86400));

        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $pdo->prepare(
            'INSERT INTO user_sessions (id, user_id, ip_address, user_agent, expires_at, created_at) 
             VALUES (:id, :user_id, :ip_address, :user_agent, :expires_at, :created_at)'
        );
        $stmt->execute([
            ':id' => $sessionToken,
            ':user_id' => $userId,
            ':ip_address' => $ipAddress,
            ':user_agent' => $userAgent,
            ':expires_at' => $expiresAt,
            ':created_at' => gmdate('c'),
        ]);

        // Set cookie
        $cookieConfig = App::config('app.cookie', []);
        setcookie(
            self::SESSION_COOKIE_NAME,
            $sessionToken,
            [
                'expires' => time() + (self::SESSION_DURATION_DAYS * 86400),
                'path' => '/',
                'secure' => $cookieConfig['secure'] ?? false,
                'httponly' => $cookieConfig['http_only'] ?? true,
                'samesite' => $cookieConfig['same_site'] ?? 'Lax',
            ]
        );

        // Load user data
        self::$currentUser = self::loadUser($userId);
        
        return $sessionToken;
    }

    public static function logout(): void
    {
        $sessionToken = $_COOKIE[self::SESSION_COOKIE_NAME] ?? null;
        
        if ($sessionToken) {
            $pdo = Db::pdo();
            $pdo->prepare('DELETE FROM user_sessions WHERE id = :id')
                ->execute([':id' => $sessionToken]);
        }

        setcookie(self::SESSION_COOKIE_NAME, '', time() - 3600, '/');
        self::$currentUser = null;
    }

    public static function register(string $email, string $password, string $name): array
    {
        $pdo = Db::pdo();
        
        // Check if user exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            throw new RuntimeException('Email already registered');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $now = gmdate('c');

        $stmt = $pdo->prepare(
            'INSERT INTO users (email, password_hash, name, role, created_at, updated_at) 
             VALUES (:email, :password_hash, :name, :role, :created_at, :updated_at)'
        );
        $stmt->execute([
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':name' => $name,
            ':role' => 'user',
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        $userId = (int)$pdo->lastInsertId();
        return self::loadUser($userId);
    }

    public static function attempt(string $email, string $password): ?array
    {
        $pdo = Db::pdo();
        $stmt = $pdo->prepare('SELECT id, email, password_hash, name FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !$user['password_hash']) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return self::loadUser((int)$user['id']);
    }

    public static function loginEmailOnly(string $email): array
    {
        $pdo = Db::pdo();
        $stmt = $pdo->prepare('SELECT id, password_hash, role FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (($user['role'] ?? '') === 'admin') {
                throw new RuntimeException('Accès non autorisé.');
            }

            self::login((int)$user['id'], true);
            return self::loadUser((int)$user['id']);
        }

        $name = self::nameFromEmail($email);
        $now = gmdate('c');

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO users (email, password_hash, name, role, email_verified, created_at, updated_at) '
                . 'VALUES (:email, :password_hash, :name, :role, :email_verified, :created_at, :updated_at)'
            );
            $stmt->execute([
                ':email' => $email,
                ':password_hash' => null,
                ':name' => $name,
                ':role' => 'user',
                ':email_verified' => 0,
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        } catch (\Throwable $e) {
            $stmt = $pdo->prepare('SELECT id, password_hash, role FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            if (!$user) {
                throw $e;
            }
            if (($user['role'] ?? '') === 'admin') {
                throw new RuntimeException('Accès non autorisé.');
            }
        }

        $userId = (is_array($user) && isset($user['id'])) ? (int)$user['id'] : (int)$pdo->lastInsertId();
        self::login($userId, true);
        return self::loadUser($userId);
    }

    public static function findOrCreateGoogleUser(string $googleId, string $email, string $name, ?string $avatarUrl = null): array
    {
        $pdo = Db::pdo();
        
        // Try to find by google_id
        $stmt = $pdo->prepare('SELECT id FROM users WHERE google_id = :google_id');
        $stmt->execute([':google_id' => $googleId]);
        $user = $stmt->fetch();

        if ($user) {
            $userId = (int)$user['id'];
            // Update user info
            $stmt = $pdo->prepare(
                'UPDATE users SET email = :email, name = :name, avatar_url = :avatar_url, email_verified = 1, updated_at = :updated_at 
                 WHERE id = :id'
            );
            $stmt->execute([
                ':email' => $email,
                ':name' => $name,
                ':avatar_url' => $avatarUrl,
                ':updated_at' => gmdate('c'),
                ':id' => $userId,
            ]);
            return self::loadUser($userId);
        }

        // Try to find by email (link account)
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $userId = (int)$user['id'];
            // Link Google account
            $stmt = $pdo->prepare(
                'UPDATE users SET google_id = :google_id, avatar_url = :avatar_url, email_verified = 1, updated_at = :updated_at 
                 WHERE id = :id'
            );
            $stmt->execute([
                ':google_id' => $googleId,
                ':avatar_url' => $avatarUrl,
                ':updated_at' => gmdate('c'),
                ':id' => $userId,
            ]);
            return self::loadUser($userId);
        }

        // Create new user
        $now = gmdate('c');
        $stmt = $pdo->prepare(
            'INSERT INTO users (email, google_id, name, role, avatar_url, email_verified, created_at, updated_at) 
             VALUES (:email, :google_id, :name, :role, :avatar_url, 1, :created_at, :updated_at)'
        );
        $stmt->execute([
            ':email' => $email,
            ':google_id' => $googleId,
            ':name' => $name,
            ':role' => 'user',
            ':avatar_url' => $avatarUrl,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        $userId = (int)$pdo->lastInsertId();
        return self::loadUser($userId);
    }

    private static function validateSession(string $sessionToken): ?array
    {
        $pdo = Db::pdo();
        
        // Clean expired sessions
        $pdo->exec('DELETE FROM user_sessions WHERE expires_at < ' . $pdo->quote(gmdate('c')));

        $stmt = $pdo->prepare(
            'SELECT s.user_id FROM user_sessions s 
             WHERE s.id = :token AND s.expires_at > :now'
        );
        $stmt->execute([':token' => $sessionToken, ':now' => gmdate('c')]);
        $session = $stmt->fetch();

        if (!$session) {
            return null;
        }

        return self::loadUser((int)$session['user_id']);
    }

    private static function loadUser(int $userId): ?array
    {
        $pdo = Db::pdo();
        $stmt = $pdo->prepare('SELECT id, email, name, role, google_id, avatar_url, email_verified, created_at FROM users WHERE id = :id');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    private static function nameFromEmail(string $email): string
    {
        $local = trim(strtolower((string)explode('@', $email, 2)[0]));
        if ($local === '') {
            return 'Visiteur';
        }
        $local = preg_replace('/[._-]+/', ' ', $local);
        $local = trim((string)$local);
        if ($local === '') {
            return 'Visiteur';
        }
        return ucwords($local);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }

    public static function isAdmin(): bool
    {
        if (!self::check()) {
            return false;
        }
        return (self::$currentUser['role'] ?? null) === 'admin';
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            http_response_code(403);
            echo View::render('errors/403', [
                'page' => ['title' => '403'],
            ]);
            exit;
        }
    }
}
