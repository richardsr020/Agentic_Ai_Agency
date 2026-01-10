<?php

declare(strict_types=1);

use App\Core\Trans;
use App\Core\View;

use function App\Core\e;

?><section class="page-section" id="<?= e((string)($id ?? 'contact')) ?>">
  <div class="container">
    <?= ($page['body_html'] ?? '') ?>

    <?= View::partial('partials/icon-strip', ['slug' => 'contact']) ?>
    <?= View::partial('partials/section-illustration', ['slug' => 'contact']) ?>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error"><?= e((string)$error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= e((string)$success) ?></div>
    <?php endif; ?>

    <div class="card reveal contact-cta">
      <h2><?= e(Trans::get('ui','book_call')) ?></h2>
      <p class="lead">Parlez avec notre agent IA (Skill). Il vous pose quelques questions pour comprendre vos besoins et établir votre profil.</p>
      <div class="cta-row">
        <button class="btn btn-primary" type="button" data-open-chat>Parlons de vos besoins</button>
      </div>
    </div>
  </div>
</section>
