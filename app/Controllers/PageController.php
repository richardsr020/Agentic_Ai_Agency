<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Db;
use App\Core\Visitor;
use App\Core\View;
use App\Models\PageRepository;

final class PageController
{
    public function home(): string
    {
        return $this->renderOnePage();
    }

    public function solutions(): string
    {
        $this->redirectToSection('agent-support');
        return '';
    }

    public function integrationLevels(): string
    {
        $this->redirectToSection('agent-scheduling');
        return '';
    }

    public function howItWorks(): string
    {
        $this->redirectToSection('agent-prospecting');
        return '';
    }

    public function useCases(): string
    {
        $this->redirectToSection('agent-support');
        return '';
    }

    public function about(): string
    {
        $this->redirectToSection('home');
        return '';
    }

    public function contact(): string
    {
        $this->redirectToSection('home');
        return '';
    }

    public function agentSupport(): string
    {
        return View::render('pages/agent-support', [
            'page' => ['title' => 'Agent IA de Service Client'],
        ]);
    }

    public function agentScheduling(): string
    {
        return View::render('pages/agent-scheduling', [
            'page' => ['title' => 'Agent IA de Prise de Rendez-vous'],
        ]);
    }

    public function agentProspecting(): string
    {
        return View::render('pages/agent-prospecting', [
            'page' => ['title' => 'Agent IA de Prospection & Qualification'],
        ]);
    }

    public function checkout(): string
    {
        return View::render('pages/checkout', [
            'page' => ['title' => 'Checkout'],
        ]);
    }

    private function renderOnePage(?string $error = null, ?string $success = null): string
    {
        $slugs = ['home'];
        $pages = PageRepository::getManyBySlug($slugs);

        if (empty($pages['home'])) {
            http_response_code(500);
            return View::render('errors/404', ['title' => 'Missing content']);
        }

        return View::render('pages/onepage', [
            'page' => $pages['home'],
            'pages' => $pages,
            'error' => $error,
            'success' => $success,
        ]);
    }

    private function redirectToSection(string $section): void
    {
        header('Location: /#' . $section);
        exit;
    }
}
