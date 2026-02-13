<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

final class Db
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            throw new RuntimeException('Database not initialized');
        }
        return self::$pdo;
    }

    public static function init(): void
    {
        $path = App::config('db.database_path');
        if (!is_string($path) || $path === '') {
            throw new RuntimeException('Missing db.database_path');
        }

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        self::$pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        self::$pdo->exec('PRAGMA foreign_keys = ON');

        self::migrate();

        self::seedAdmin();
    }

    private static function migrate(): void
    {
        $pdo = self::pdo();
        $pdo->exec('CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL UNIQUE, applied_at TEXT NOT NULL)');

        $migrationsPath = App::config('db.migrations_path');
        if (!is_string($migrationsPath) || $migrationsPath === '' || !is_dir($migrationsPath)) {
            return;
        }

        $files = glob(rtrim($migrationsPath, '/') . '/*.sql') ?: [];
        sort($files);

        $stmt = $pdo->prepare('SELECT name FROM migrations');
        $stmt->execute();
        $applied = array_fill_keys(array_column($stmt->fetchAll(), 'name'), true);

        $insert = $pdo->prepare('INSERT INTO migrations (name, applied_at) VALUES (:name, :applied_at)');

        foreach ($files as $file) {
            $name = basename($file);
            if (isset($applied[$name])) {
                continue;
            }

            $sql = file_get_contents($file);
            if ($sql === false) {
                continue;
            }

            $pdo->beginTransaction();
            try {
                $pdo->exec($sql);
                $insert->execute([':name' => $name, ':applied_at' => gmdate('c')]);
                $pdo->commit();
            } catch (\Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }
        }
    }

    private static function seedAdmin(): void
    {
        $pdo = self::pdo();

        try {
            $stmt = $pdo->query('SELECT COUNT(1) AS c FROM users');
            $row = $stmt ? $stmt->fetch() : null;
            $count = (int)($row['c'] ?? 0);
        } catch (\Throwable $e) {
            return;
        }

        if ($count > 0) {
            return;
        }

        $email = 'admin@agentic-ai.local';
        $passwordHash = password_hash('admin123456', PASSWORD_DEFAULT);
        $now = gmdate('c');

        $insert = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, email_verified, created_at, updated_at) VALUES (:email, :password_hash, :name, :role, :email_verified, :created_at, :updated_at)');
        $insert->execute([
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':name' => 'Admin',
            ':role' => 'admin',
            ':email_verified' => 1,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
    }
}
