<?php

declare(strict_types=1);

use App\Core\View;

?><section class="page-section" id="<?= App\Core\e((string)($id ?? '')) ?>">
  <div class="container">
    <?= $body ?? '' ?>
    <?= View::partial('partials/icon-strip', ['slug' => (string)($slug ?? '')]) ?>
    <?= View::partial('partials/section-illustration', ['slug' => (string)($slug ?? '')]) ?>
  </div>
</section>
