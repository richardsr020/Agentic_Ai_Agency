<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Db;
use PDO;
use PDOException;

final class ChatController
{
    public function sendMessage(): void
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input') ?: '{}', true);
            $message = trim($data['message'] ?? '');

            if (empty($message)) {
                http_response_code(400);
                echo json_encode(['error' => 'Message requis']);
                return;
            }

            $userId = Auth::id();
            if (!$userId) {
                $email = $this->extractEmail($message);
                if (!$email) {
                    echo json_encode(['response' => $this->buildEmailRequest()]);
                    return;
                }

                try {
                    Auth::loginEmailOnly($email);
                } catch (\RuntimeException $e) {
                    echo json_encode(['response' => $e->getMessage()]);
                    return;
                }
                $userId = Auth::id();
                if (!$userId) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Erreur serveur']);
                    return;
                }

                $pdo = Db::pdo();
                $conversation = $this->getOrCreateConversation($pdo, $userId);
                $this->saveMessage($pdo, $conversation['id'], 'user', $message);
                $greeting = $this->buildGreeting();
                $this->saveMessage($pdo, $conversation['id'], 'assistant', $greeting);
                $this->updateConversation($pdo, $conversation['id']);

                echo json_encode(['response' => $greeting]);
                return;
            }

            $pdo = Db::pdo();

            // Get or create active conversation
            $conversation = $this->getOrCreateConversation($pdo, $userId);

            $history = $this->getConversationHistory($pdo, $conversation['id']);
            if (count($history) === 0) {
                $this->saveMessage($pdo, $conversation['id'], 'assistant', $this->buildGreeting());
                $history = $this->getConversationHistory($pdo, $conversation['id']);
            }

            // Save user message
            $this->saveMessage($pdo, $conversation['id'], 'user', $message);

            // Get conversation history
            $history = $this->getConversationHistory($pdo, $conversation['id']);

            // Get system prompt
            $systemPrompt = $this->getSystemPrompt();

            // Call Gemini API
            $response = $this->callGemini($systemPrompt, $history, $message);
            $redirect = $this->extractRedirect($response);
            if ($redirect) {
                $response = $this->stripRedirectToken($response);
            }

            // Save assistant response
            $this->saveMessage($pdo, $conversation['id'], 'assistant', $response);

            // Update conversation
            $this->updateConversation($pdo, $conversation['id']);

            // Extract and save profile data if relevant
            $this->extractAndSaveProfile($pdo, $userId, $response);

            echo json_encode([
                'response' => $response,
                'redirect' => $redirect,
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('Chat error: ' . $e->getMessage());
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function getHistory(): void
    {
        header('Content-Type: application/json');

        try {
            $userId = Auth::id();
            if (!$userId) {
                echo json_encode(['messages' => [
                    ['role' => 'assistant', 'content' => $this->buildEmailRequest()],
                ]]);
                return;
            }
            $pdo = Db::pdo();

            $stmt = $pdo->prepare(
                'SELECT id FROM chat_conversations WHERE user_id = :user_id AND status = "active" ORDER BY updated_at DESC LIMIT 1'
            );
            $stmt->execute([':user_id' => $userId]);
            $conversation = $stmt->fetch();

            if (!$conversation) {
                $conversation = $this->getOrCreateConversation($pdo, $userId);
            }

            $messages = $this->getConversationHistory($pdo, $conversation['id']);

            if (count($messages) === 0) {
                $this->saveMessage($pdo, $conversation['id'], 'assistant', $this->buildGreeting());
                $messages = $this->getConversationHistory($pdo, $conversation['id']);
            }

            echo json_encode(['messages' => $messages]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }

    private function buildGreeting(): string
    {
        $user = Auth::user();
        $rawName = trim((string)($user['name'] ?? ''));
        $email = trim((string)($user['email'] ?? ''));

        $displayName = '';
        if ($rawName !== '') {
            $displayName = $rawName;
        } elseif ($email !== '' && strpos($email, '@') !== false) {
            $local = explode('@', $email, 2)[0];
            $local = preg_replace('/[._-]+/', ' ', $local);
            $local = trim((string)$local);
            $displayName = ucwords(strtolower($local));
        }

        $maybeCompany = false;
        $emailLocal = $email !== '' && strpos($email, '@') !== false ? strtolower(explode('@', $email, 2)[0]) : '';
        if ($emailLocal !== '') {
            $maybeCompany = (bool)preg_match('/\b(info|contact|support|sales|hello|admin|service)\b/', $emailLocal);
        }

        $who = 'Je suis Skill, votre conseiller business chez Agentic_AI.';
        if ($displayName !== '' && !$maybeCompany) {
            return "Bonjour {$displayName} ! {$who} Pour bien vous orienter, j'ai une première question : dans quel secteur d'activité êtes-vous ?";
        }

        return "Bonjour ! {$who} Pour bien vous orienter, j'ai une première question : dans quel secteur d'activité êtes-vous ?";
    }

    private function buildEmailRequest(): string
    {
        return "Bonjour ! Je suis Skill, votre conseiller business chez Agentic_AI. Pour démarrer la discussion, quelle est votre adresse email ?";
    }

    private function extractEmail(string $message): ?string
    {
        if (!preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message, $matches)) {
            return null;
        }
        $email = strtolower($matches[0]);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function getOrCreateConversation(PDO $pdo, int $userId): array
    {
        // Try to get active conversation
        $stmt = $pdo->prepare(
            'SELECT id, user_id, status FROM chat_conversations WHERE user_id = :user_id AND status = "active" ORDER BY updated_at DESC LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        $conversation = $stmt->fetch();

        if ($conversation) {
            return $conversation;
        }

        // Create new conversation
        $now = gmdate('c');
        $stmt = $pdo->prepare(
            'INSERT INTO chat_conversations (user_id, status, created_at, updated_at) VALUES (:user_id, "active", :created_at, :updated_at)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return [
            'id' => (int)$pdo->lastInsertId(),
            'user_id' => $userId,
            'status' => 'active',
        ];
    }

    private function saveMessage(PDO $pdo, int $conversationId, string $role, string $content): void
    {
        // Use transaction to prevent concurrent writes
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO chat_messages (conversation_id, role, content, created_at) VALUES (:conversation_id, :role, :content, :created_at)'
            );
            $stmt->execute([
                ':conversation_id' => $conversationId,
                ':role' => $role,
                ':content' => $content,
                ':created_at' => gmdate('c'),
            ]);
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private function getConversationHistory(PDO $pdo, int $conversationId): array
    {
        $stmt = $pdo->prepare(
            'SELECT role, content FROM chat_messages WHERE conversation_id = :conversation_id ORDER BY created_at ASC'
        );
        $stmt->execute([':conversation_id' => $conversationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateConversation(PDO $pdo, int $conversationId): void
    {
        $stmt = $pdo->prepare('UPDATE chat_conversations SET updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            ':updated_at' => gmdate('c'),
            ':id' => $conversationId,
        ]);
    }

    private function getSystemPrompt(): string
    {
        $promptFile = __DIR__ . '/../../config/system_prompt.txt';
        if (file_exists($promptFile)) {
            return trim(file_get_contents($promptFile));
        }
        return 'You are a helpful business advisor for Agentic_AI.';
    }

    private function callGemini(string $systemPrompt, array $history, string $userMessage): string
    {
        $apiKey = \App\Core\App::config('gemini.api_key');
        $apiUrl = \App\Core\App::config('gemini.api_url');

        if (!$apiKey || !$apiUrl) {
            throw new \RuntimeException('Gemini API not configured');
        }

        // Build conversation contents
        $contents = [];
        
        // Add system instruction (Gemini uses systemInstruction in the request)
        // For now, prepend to first user message
        $firstUserMsg = true;

        // Add conversation history
        foreach ($history as $msg) {
            if ($msg['role'] === 'user') {
                $text = $msg['content'];
                if ($firstUserMsg) {
                    $text = $systemPrompt . "\n\n" . $text;
                    $firstUserMsg = false;
                }
                $contents[] = ['role' => 'user', 'parts' => [['text' => $text]]];
            } else {
                $contents[] = ['role' => 'model', 'parts' => [['text' => $msg['content']]]];
            }
        }

        // Add current user message
        $currentText = $userMessage;
        if ($firstUserMsg) {
            $currentText = $systemPrompt . "\n\n" . $userMessage;
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $currentText]]];

        $requestData = [
            'contents' => $contents,
        ];

        $url = $apiUrl . '?key=' . urlencode($apiKey);
        $payload = json_encode($requestData);

        if (function_exists('curl_init')) {
            $ch = \curl_init($url);
            \curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT => 60,
            ]);

            $response = \curl_exec($ch);
            $httpCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = \curl_error($ch);
            \curl_close($ch);

            if ($error) {
                throw new \RuntimeException('CURL error: ' . $error);
            }

            if ($httpCode !== 200) {
                error_log('Gemini API error: HTTP ' . $httpCode . ' - ' . $response);
                throw new \RuntimeException('API error: HTTP ' . $httpCode);
            }
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => $payload,
                    'timeout' => 60,
                    'ignore_errors' => true,
                ],
            ]);

            $response = file_get_contents($url, false, $context);
            $statusLine = $http_response_header[0] ?? '';
            $httpCode = 0;
            if (preg_match('/HTTP\/(?:1\.\d|2)\s+(\d+)/', $statusLine, $m)) {
                $httpCode = (int)$m[1];
            }

            if ($response === false) {
                throw new \RuntimeException('HTTP request failed (cURL extension missing)');
            }

            if ($httpCode !== 200) {
                error_log('Gemini API error (no-curl): HTTP ' . $httpCode . ' - ' . $response);
                throw new \RuntimeException('API error: HTTP ' . $httpCode);
            }
        }

        $data = json_decode($response, true);
        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            error_log('Gemini API invalid response: ' . json_encode($data));
            throw new \RuntimeException('Invalid API response');
        }

        return trim($data['candidates'][0]['content']['parts'][0]['text']);
    }

    private function extractAndSaveProfile(PDO $pdo, int $userId, string $response): void
    {
        // This is a simple implementation - in production, you'd use more sophisticated NLP
        // For now, we'll just update the profile_data JSON field periodically
        // In a real implementation, you'd parse the response to extract structured data
        
        $stmt = $pdo->prepare('SELECT id FROM user_profiles WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        $profile = $stmt->fetch();

        $now = gmdate('c');
        $profileData = ['last_interaction' => $now, 'last_response' => $response];

        if ($profile) {
            $stmt = $pdo->prepare(
                'UPDATE user_profiles SET profile_data = :profile_data, updated_at = :updated_at WHERE user_id = :user_id'
            );
            $stmt->execute([
                ':profile_data' => json_encode($profileData),
                ':updated_at' => $now,
                ':user_id' => $userId,
            ]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO user_profiles (user_id, profile_data, created_at, updated_at) VALUES (:user_id, :profile_data, :created_at, :updated_at)'
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':profile_data' => json_encode($profileData),
                ':created_at' => $now,
                ':updated_at' => $now,
            ]);
        }
    }

    private function extractRedirect(string $response): ?string
    {
        if (preg_match('/\[\[redirect:([^\]]+)\]\]/i', $response, $matches)) {
            $path = trim($matches[1]);
            if ($path !== '' && $path[0] === '/') {
                return $path;
            }
        }
        return null;
    }

    private function stripRedirectToken(string $response): string
    {
        return trim(preg_replace('/\s*\[\[redirect:[^\]]+\]\]\s*/i', ' ', $response));
    }
}
