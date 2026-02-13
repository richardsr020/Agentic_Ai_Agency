<?php

declare(strict_types=1);

use function App\Core\e;

$conversations = $conversations ?? [];
$selectedConversation = $selectedConversation ?? null;
$messages = $messages ?? [];

?><article class="page-section">
  <div class="container">
    <div class="cta-row" style="justify-content:space-between; align-items:center;">
      <h1 style="margin:0;">Chats</h1>
      <a class="btn btn-ghost" href="/admin">Retour</a>
    </div>

    <div style="display:grid; grid-template-columns: 360px 1fr; gap:14px; margin-top:14px;">
      <div style="border-radius:14px; border:1px solid rgba(255,255,255,.08); overflow:hidden;">
        <div style="padding:10px 12px; background:rgba(255,255,255,.04); font-weight:600;">Conversations</div>
        <div style="max-height:62vh; overflow:auto;">
          <?php foreach ($conversations as $c): ?>
            <a href="/admin/chats?conversation_id=<?= e((string)$c['id']) ?>" style="display:block; padding:10px 12px; border-top:1px solid rgba(255,255,255,.06); text-decoration:none; color:inherit;">
              <div style="font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <?= e((string)($c['name'] ?? '')) ?>
                <span style="opacity:.75; font-weight:400;">(<?= e((string)$c['email']) ?>)</span>
              </div>
              <div style="opacity:.8; font-size:.9rem;">#<?= e((string)$c['id']) ?> • <?= e((string)($c['status'] ?? '')) ?> • <?= e((string)($c['message_count'] ?? 0)) ?> msg</div>
              <div style="opacity:.75; font-size:.85rem;">Dernière activité: <?= e((string)($c['updated_at'] ?? '')) ?></div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div style="border-radius:14px; border:1px solid rgba(255,255,255,.08); overflow:hidden;">
        <div style="padding:10px 12px; background:rgba(255,255,255,.04); font-weight:600;">
          <?php if ($selectedConversation): ?>
            Conversation #<?= e((string)$selectedConversation['id']) ?>
            <span style="opacity:.75; font-weight:400;">— <?= e((string)($selectedConversation['name'] ?? '')) ?> (<?= e((string)$selectedConversation['email']) ?>)</span>
          <?php else: ?>
            Sélectionne une conversation
          <?php endif; ?>
        </div>

        <div style="padding:12px; max-height:62vh; overflow:auto; display:flex; flex-direction:column; gap:10px;">
          <?php if (!$selectedConversation): ?>
            <div style="opacity:.85;">Choisis une conversation à gauche.</div>
          <?php else: ?>
            <?php foreach ($messages as $m): ?>
              <div style="display:flex; <?= ($m['role'] ?? '') === 'user' ? 'justify-content:flex-end' : 'justify-content:flex-start' ?>;">
                <div style="max-width:78%; padding:10px 12px; border-radius:14px; line-height:1.45; background:<?= ($m['role'] ?? '') === 'user' ? 'linear-gradient(135deg, rgba(91,140,255,.35), rgba(124,91,255,.28))' : 'rgba(255,255,255,.06)' ?>;">
                  <div style="white-space:pre-wrap;"><?= e((string)($m['content'] ?? '')) ?></div>
                  <div style="opacity:.7; font-size:.8rem; margin-top:6px;"><?= e((string)($m['created_at'] ?? '')) ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</article>
