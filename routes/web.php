<?php

declare(strict_types=1);

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
