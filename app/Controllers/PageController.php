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
        $this->redirectToSection('contact');
        return '';
    }

    public function submitContact(): string
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $company = trim((string)($_POST['company'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($name === '' || $email === '' || $message === '') {
            http_response_code(400);
            return $this->renderOnePage('Please complete required fields.', null);
        }

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('INSERT INTO contact_submissions (visitor_id, name, email, company, message, created_at) VALUES (:visitor_id, :name, :email, :company, :message, :created_at)');
        $stmt->execute([
            ':visitor_id' => Visitor::current()['id'] ?? null,
            ':name' => $name,
            ':email' => $email,
            ':company' => ($company === '' ? null : $company),
            ':message' => $message,
            ':created_at' => gmdate('c'),
        ]);

        return $this->renderOnePage(null, 'Thanks — we will reply shortly.');
    }

    public function submitBooking(): string
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $company = trim((string)($_POST['company'] ?? ''));
        $preferredDate = trim((string)($_POST['preferred_date'] ?? ''));
        $notes = trim((string)($_POST['notes'] ?? ''));

        if ($name === '' || $email === '') {
            http_response_code(400);
            return $this->renderOnePage('Please complete required fields.', null);
        }

        $pdo = Db::pdo();
        $stmt = $pdo->prepare('INSERT INTO discovery_calls (visitor_id, name, email, company, preferred_date, notes, created_at) VALUES (:visitor_id, :name, :email, :company, :preferred_date, :notes, :created_at)');
        $stmt->execute([
            ':visitor_id' => Visitor::current()['id'] ?? null,
            ':name' => $name,
            ':email' => $email,
            ':company' => ($company === '' ? null : $company),
            ':preferred_date' => ($preferredDate === '' ? null : $preferredDate),
            ':notes' => ($notes === '' ? null : $notes),
            ':created_at' => gmdate('c'),
        ]);

        return $this->renderOnePage(null, 'Request received — we will confirm by email.');
    }

    private function renderOnePage(?string $error = null, ?string $success = null): string
    {
        $slugs = ['home', 'contact'];
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
