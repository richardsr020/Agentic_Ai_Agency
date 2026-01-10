<?php

declare(strict_types=1);

use function App\Core\e;

$slug = (string)($slug ?? '');

$map = [
    'home' => 'i-workflow',
    'solutions' => 'i-gear',
    'integration-levels' => 'i-plug',
    'how-it-works' => 'i-workflow',
    'use-cases' => 'i-briefcase',
    'about' => 'i-shield',
    'contact' => 'i-graph',
];

$icon = $map[$slug] ?? 'i-workflow';

?>
<div class="illus reveal" aria-hidden="true">
  <div class="illus-ring"></div>
  <div class="illus-dots"></div>
  <svg class="illus-icon"><use href="#<?= e((string)$icon) ?>"></use></svg>
</div>
