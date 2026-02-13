<?php

declare(strict_types=1);

use App\Core\Auth;

use function App\Core\e;

$stats = $stats ?? ['users' => 0, 'conversations' => 0, 'messages' => 0];
$user = Auth::user();

?><article class="page-section">
  <div class="container">
    <h1>Admin</h1>
    <p class="lead">Bienvenue<?= $user && !empty($user['name']) ? ' ' . e((string)$user['name']) : '' ?>.</p>

    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; margin-top:16px;">
      <div class="card" style="padding:14px; border-radius:14px; background:rgba(255,255,255,.04);">
        <div style="opacity:.85;">Utilisateurs</div>
        <div style="font-size:28px; font-weight:650; margin-top:4px;"><?= e((string)$stats['users']) ?></div>
      </div>
      <div class="card" style="padding:14px; border-radius:14px; background:rgba(255,255,255,.04);">
        <div style="opacity:.85;">Conversations</div>
        <div style="font-size:28px; font-weight:650; margin-top:4px;"><?= e((string)$stats['conversations']) ?></div>
      </div>
      <div class="card" style="padding:14px; border-radius:14px; background:rgba(255,255,255,.04);">
        <div style="opacity:.85;">Messages</div>
        <div style="font-size:28px; font-weight:650; margin-top:4px;"><?= e((string)$stats['messages']) ?></div>
      </div>
    </div>

    <div class="cta-row" style="margin-top:18px;">
      <a class="btn btn-primary" href="/admin/users">Gérer les utilisateurs</a>
      <a class="btn btn-ghost" href="/admin/chats">Voir les chats</a>
    </div>
  </div>
</article>
