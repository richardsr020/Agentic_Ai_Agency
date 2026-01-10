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

$router->post('/preferences/language', [PreferenceController::class, 'setLanguage']);
$router->post('/preferences/theme', [PreferenceController::class, 'setTheme']);

$router->post('/contact/submit', [PageController::class, 'submitContact']);
$router->post('/book/submit', [PageController::class, 'submitBooking']);
