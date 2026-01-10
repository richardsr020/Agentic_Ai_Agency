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

    <div class="modal" data-chat-modal aria-hidden="true">
      <div class="modal-backdrop" data-close-chat></div>
      <div class="modal-panel" role="dialog" aria-modal="true" aria-label="Chat Skill">
        <div class="modal-header">
          <div class="modal-title">Skill</div>
          <button class="modal-close" type="button" data-close-chat aria-label="Close">×</button>
        </div>
        <div class="chat">
          <div class="chat-messages" data-chat-messages></div>
          <form class="chat-input" data-chat-form>
            <input name="message" autocomplete="off" placeholder="Écrivez votre réponse…" data-chat-input />
            <button class="btn btn-primary" type="submit">Envoyer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
