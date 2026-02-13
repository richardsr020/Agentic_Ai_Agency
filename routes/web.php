<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ChatController;
use App\Controllers\PageController;
use App\Controllers\PreferenceController;

$router->get('/', [PageController::class, 'home']);
$router->get('/solutions', [PageController::class, 'solutions']);
$router->get('/integration-levels', [PageController::class, 'integrationLevels']);
$router->get('/how-it-works', [PageController::class, 'howItWorks']);
$router->get('/use-cases', [PageController::class, 'useCases']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/contact', [PageController::class, 'contact']);

$router->get('/agent-support', [PageController::class, 'agentSupport']);
$router->get('/agent-scheduling', [PageController::class, 'agentScheduling']);
$router->get('/agent-prospecting', [PageController::class, 'agentProspecting']);
$router->get('/checkout', [PageController::class, 'checkout']);

$router->post('/preferences/language', [PreferenceController::class, 'setLanguage']);
$router->post('/preferences/theme', [PreferenceController::class, 'setTheme']);

// Auth routes
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->post('/api/auth/email', [AuthController::class, 'emailLogin']);
$router->post('/api/auth/logout', [AuthController::class, 'logout']);
$router->get('/api/auth/user', [AuthController::class, 'user']);
$router->get('/api/auth/google', [AuthController::class, 'googleCallback']);

// Chat routes
$router->post('/api/chat/message', [ChatController::class, 'sendMessage']);
$router->get('/api/chat/history', [ChatController::class, 'getHistory']);

// Admin routes
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->post('/admin/users/role', [AdminController::class, 'updateUserRole']);
$router->post('/admin/users/cleanup', [AdminController::class, 'cleanupGuests']);
$router->get('/admin/chats', [AdminController::class, 'chats']);
