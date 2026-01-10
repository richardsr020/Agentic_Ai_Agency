<?php

declare(strict_types=1);

use function App\Core\e;

$slug = (string)($slug ?? '');

$sets = [
    'home' => [
        ['i-workflow', 'Operational workflows'],
        ['i-graph', 'Measured impact'],
        ['i-shield', 'Secure by design'],
        ['i-plug', 'Integrates cleanly'],
    ],
    'solutions' => [
        ['i-briefcase', 'Ops Coordinator'],
        ['i-graph', 'Revenue Assistant'],
        ['i-gear', 'Support Automator'],
        ['i-workflow', 'Orchestration'],
    ],
    'integration-levels' => [
        ['i-plug', 'Level 1'],
        ['i-gear', 'Level 2'],
        ['i-workflow', 'Level 3'],
        ['i-shield', 'Level 4'],
    ],
    'how-it-works' => [
        ['i-workflow', 'Scope'],
        ['i-shield', 'Controls'],
        ['i-graph', 'Measure'],
        ['i-gear', 'Iterate'],
    ],
    'use-cases' => [
        ['i-briefcase', 'Services'],
        ['i-plug', 'Logistics'],
        ['i-gear', 'SaaS'],
        ['i-graph', 'Ops metrics'],
    ],
    'about' => [
        ['i-shield', 'Reliability'],
        ['i-graph', 'Outcomes'],
        ['i-workflow', 'Systems'],
        ['i-briefcase', 'Consulting'],
    ],
];

$items = $sets[$slug] ?? null;
if (!$items) {
    return;
}

?>
<section class="icon-strip">
  <div class="container icon-strip-inner">
    <?php foreach ($items as [$icon, $label]): ?>
      <div class="icon-badge">
        <svg class="icon" aria-hidden="true"><use href="#<?= e((string)$icon) ?>"></use></svg>
        <div class="icon-label"><?= e((string)$label) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
