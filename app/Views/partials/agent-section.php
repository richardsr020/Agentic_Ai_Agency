<?php

declare(strict_types=1);

use function App\Core\e;

$title = (string)($title ?? '');
$subtitle = (string)($subtitle ?? '');
$id = (string)($id ?? '');
$image = (string)($image ?? '');
$bullets = $bullets ?? [];

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
          <a class="btn btn-primary" href="/#contact">Get a quote</a>
          <a class="btn btn-ghost" href="/#contact">Book a call</a>
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
