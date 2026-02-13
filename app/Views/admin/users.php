<?php

declare(strict_types=1);

use function App\Core\e;

$users = $users ?? [];
$guestCount = (int)($guestCount ?? 0);

?><article class="page-section">
  <div class="container">
    <div class="cta-row" style="justify-content:space-between; align-items:center;">
      <h1 style="margin:0;">Utilisateurs</h1>
      <a class="btn btn-ghost" href="/admin">Retour</a>
    </div>

    <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
      <div style="opacity:.9;">Comptes guest détectés: <strong><?= e((string)$guestCount) ?></strong></div>
      <?php if ($guestCount > 0): ?>
        <form method="post" action="/admin/users/cleanup" onsubmit="return confirm('Supprimer tous les comptes guest de test ?')">
          <button class="btn btn-primary" type="submit">Nettoyer les guests</button>
        </form>
      <?php endif; ?>
    </div>

    <div style="margin-top:14px; overflow:auto; border-radius:14px; border:1px solid rgba(255,255,255,.08);">
      <table style="width:100%; border-collapse:collapse; min-width:820px;">
        <thead>
          <tr style="text-align:left; background:rgba(255,255,255,.04);">
            <th style="padding:10px 12px;">ID</th>
            <th style="padding:10px 12px;">Nom</th>
            <th style="padding:10px 12px;">Email</th>
            <th style="padding:10px 12px;">Rôle</th>
            <th style="padding:10px 12px;">Créé</th>
            <th style="padding:10px 12px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr style="border-top:1px solid rgba(255,255,255,.06);">
              <td style="padding:10px 12px; opacity:.9;"><?= e((string)$u['id']) ?></td>
              <td style="padding:10px 12px;"><?= e((string)($u['name'] ?? '')) ?></td>
              <td style="padding:10px 12px;"><?= e((string)$u['email']) ?></td>
              <td style="padding:10px 12px;"><strong><?= e((string)($u['role'] ?? 'user')) ?></strong></td>
              <td style="padding:10px 12px; opacity:.85;"><?= e((string)($u['created_at'] ?? '')) ?></td>
              <td style="padding:10px 12px;">
                <form method="post" action="/admin/users/role" style="display:flex; gap:8px; align-items:center;">
                  <input type="hidden" name="user_id" value="<?= e((string)$u['id']) ?>" />
                  <select name="role" class="select">
                    <option value="user" <?= ($u['role'] ?? '') === 'user' ? 'selected' : '' ?>>user</option>
                    <option value="admin" <?= ($u['role'] ?? '') === 'admin' ? 'selected' : '' ?>>admin</option>
                  </select>
                  <button class="btn btn-ghost" type="submit">Mettre à jour</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</article>
