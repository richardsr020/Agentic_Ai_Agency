<?php

declare(strict_types=1);

use function App\Core\e;

?><article>
  <?= $page['body_html'] ?? '' ?>

  <?= App\Core\View::partial('partials/icon-strip', ['slug' => $page['slug'] ?? '']) ?>
</article>
