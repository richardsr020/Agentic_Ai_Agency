<?php

declare(strict_types=1);

use function App\Core\e;

$title = (string)($title ?? '');
$subtitle = (string)($subtitle ?? '');
$id = (string)($id ?? '');
$image = (string)($image ?? '');
$bullets = $bullets ?? [];
$detailsUrl = (string)($detailsUrl ?? '');
$primaryCta = (string)($primaryCta ?? 'En savoir plus');
$secondaryCta = (string)($secondaryCta ?? 'Commencer');
$secondaryUrl = (string)($secondaryUrl ?? '/checkout');
$showBookCall = (bool)($showBookCall ?? false);

?>
<section class="page-section agent" id="<?= e($id) ?>">
  <div class="container">
    <div class="agent-grid">
      <div class="agent-copy reveal">
        <h2 class="agent-title"><?= e($title) ?></h2>
        <p class="lead agent-subtitle"><?= e($subtitle) ?></p>
        <div class="card agent-points">
          <ul class="points">
            <?php foreach ($bullets as $b): ?>
              <li><?= e((string)$b) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="cta-row">
          <a class="btn btn-primary" href="<?= e($detailsUrl) ?>"><?= e($primaryCta) ?></a>
          <?php if ($showBookCall): ?>
            <button class="btn btn-ghost" type="button" data-open-chat>Réserver un appel</button>
          <?php else: ?>
            <a class="btn btn-ghost" href="<?= e($secondaryUrl) ?>"><?= e($secondaryCta) ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="agent-art reveal" aria-hidden="true">
        <div class="illus">
          <div class="illus-ring"></div>
          <div class="illus-dots"></div>
          <img class="agent-svg" src="<?= e($image) ?>" alt="" />
        </div>
      </div>
    </div>
  </div>
</section>
