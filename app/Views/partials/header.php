<?php

declare(strict_types=1);

use App\Core\I18n;
use App\Core\Trans;
use App\Core\Visitor;

use function App\Core\e;

$lang = Visitor::language();
$theme = Visitor::theme();

$labels = [
    'en' => 'English',
    'zh' => '中文',
    'es' => 'Español',
    'fr' => 'Français',
    'ar' => 'العربية',
    'pt' => 'Português',
];

?>
<header class="header">
  <div class="container header-inner">
    <a class="brand" href="/#home">Agentic_AI</a>

    <nav class="nav">
      <a href="/#home" class="nav-link">Agents IA</a>
      <a href="/#agent-support" class="nav-link">Service Client</a>
      <a href="/#agent-scheduling" class="nav-link">Rendez-vous</a>
      <a href="/#agent-prospecting" class="nav-link">Prospection</a>
      <a href="/checkout" class="nav-link nav-cta">Checkout</a>
    </nav>

    <div class="prefs">
      <form class="pref" method="post" action="/preferences/language">
        <label class="sr-only" for="lang"><?= e(Trans::get('ui','language')) ?></label>
        <select id="lang" name="lang" class="select" onchange="this.form.submit()">
          <?php foreach (I18n::supported() as $code): ?>
            <option value="<?= e((string)$code) ?>" <?= $lang === $code ? 'selected' : '' ?>><?= e($labels[$code] ?? strtoupper((string)$code)) ?></option>
          <?php endforeach; ?>
        </select>
      </form>

      <form class="pref" method="post" action="/preferences/theme">
        <label class="sr-only" for="theme"><?= e(Trans::get('ui','theme')) ?></label>
        <select id="theme" name="theme" class="select" onchange="this.form.submit()">
          <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>><?= e(Trans::get('ui','light')) ?></option>
          <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>><?= e(Trans::get('ui','dark')) ?></option>
        </select>
      </form>

      <button class="nav-toggle" type="button" aria-label="Toggle menu" data-nav-toggle>
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
