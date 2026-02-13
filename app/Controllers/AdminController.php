<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Db;
use App\Core\View;
use PDO;

final class AdminController
{
    public function dashboard(): string
    {
        Auth::requireAdmin();

        $pdo = Db::pdo();

        $usersCount = (int)($pdo->query('SELECT COUNT(1) AS c FROM users')->fetch()['c'] ?? 0);
        $convosCount = (int)($pdo->query('SELECT COUNT(1) AS c FROM chat_conversations')->fetch()['c'] ?? 0);
        $messagesCount = (int)($pdo->query('SELECT COUNT(1) AS c FROM chat_messages')->fetch()['c'] ?? 0);

        return View::render('admin/dashboard', [
            'page' => ['title' => 'Admin'],
            'stats' => [
                'users' => $usersCount,
                'conversations' => $convosCount,
                'messages' => $messagesCount,
            ],
        ]);
    }

    public function users(): string
    {
        Auth::requireAdmin();

        $pdo = Db::pdo();
        $users = $pdo->query('SELECT id, email, name, role, created_at FROM users ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);

        $guestCount = (int)($pdo->query("SELECT COUNT(1) AS c FROM users WHERE email LIKE 'guest-%@guest.local'")->fetch()['c'] ?? 0);

        return View::render('admin/users', [
            'page' => ['title' => 'Admin - Utilisateurs'],
            'users' => $users,
            'guestCount' => $guestCount,
        ]);
    }

    public function updateUserRole(): void
    {
        Auth::requireAdmin();

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $role = isset($_POST['role']) ? (string)$_POST['role'] : '';

        if ($userId <= 0 || ($role !== 'admin' && $role !== 'user')) {
            header('Location: /admin/users');
            return;
        }

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('UPDATE users SET role = :role, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            ':role' => $role,
            ':updated_at' => gmdate('c'),
            ':id' => $userId,
        ]);

        header('Location: /admin/users');
    }

    public function cleanupGuests(): void
    {
        Auth::requireAdmin();

        $pdo = Db::pdo();
        $pdo->beginTransaction();
        try {
            $pdo->exec("DELETE FROM users WHERE email LIKE 'guest-%@guest.local'");
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
        }

        header('Location: /admin/users');
    }

    public function chats(): string
    {
        Auth::requireAdmin();

        $pdo = Db::pdo();

        $conversationId = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
        $selectedConversation = null;
        $messages = [];

        if ($conversationId > 0) {
            $stmt = $pdo->prepare(
                'SELECT c.id, c.user_id, c.status, c.created_at, c.updated_at, u.email, u.name '
                . 'FROM chat_conversations c JOIN users u ON u.id = c.user_id WHERE c.id = :id'
            );
            $stmt->execute([':id' => $conversationId]);
            $selectedConversation = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

            if ($selectedConversation) {
                $stmt = $pdo->prepare('SELECT role, content, created_at FROM chat_messages WHERE conversation_id = :id ORDER BY created_at ASC');
                $stmt->execute([':id' => $conversationId]);
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $conversations = $pdo->query(
            'SELECT c.id, c.status, c.updated_at, u.email, u.name, '
            . '(SELECT COUNT(1) FROM chat_messages m WHERE m.conversation_id = c.id) AS message_count '
            . 'FROM chat_conversations c JOIN users u ON u.id = c.user_id '
            . 'ORDER BY c.updated_at DESC'
        )->fetchAll(PDO::FETCH_ASSOC);

        return View::render('admin/chats', [
            'page' => ['title' => 'Admin - Chats'],
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'messages' => $messages,
        ]);
    }
}
