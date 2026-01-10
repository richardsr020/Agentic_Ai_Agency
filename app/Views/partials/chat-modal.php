<?php

declare(strict_types=1);

?><div class="modal" data-chat-modal aria-hidden="true">
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
