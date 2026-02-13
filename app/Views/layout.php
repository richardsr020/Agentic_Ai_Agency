<?php

declare(strict_types=1);

use App\Core\Trans;
use App\Core\Visitor;

use function App\Core\e;

$lang = Visitor::language();
$theme = Visitor::theme();
$dir = $lang === 'ar' ? 'rtl' : 'ltr';
$title = $page['title'] ?? ($title ?? 'Agentic_AI');
$metaDescription = $page['meta_description'] ?? ($metaDescription ?? '');

?><!doctype html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>" data-theme="<?= e($theme) ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= e((string)$title) ?></title>
  <?php if ($metaDescription !== ''): ?>
    <meta name="description" content="<?= e((string)$metaDescription) ?>" />
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/styles.css" />
</head>
<body>
  <?= App\Core\View::partial('partials/icons-sprite') ?>
  <?= App\Core\View::partial('partials/header') ?>
  <main class="main">
    <div id="spa-content" data-spa-content>
      <?= $content ?? '' ?>
    </div>
  </main>
  <?= App\Core\View::partial('partials/chat-modal') ?>
  <?= App\Core\View::partial('partials/footer') ?>
  <button class="chat-fab" type="button" data-open-chat aria-label="Ouvrir le chat avec Skill">
    <span class="chat-fab-label">Parler à Skill</span>
  </button>
  <script src="/assets/app.js" defer></script>
</body>
</html>
