<?php

declare(strict_types=1);

use App\Core\View;

?><article>
  <div id="home"></div>

  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy reveal">
        <h1>Des agents IA qui comprennent vos vrais besoins</h1>
        <p class="lead">Discutez avec notre agent <strong>(Skill)</strong> pour <strong>qualifier votre demande</strong>, <strong>établir votre profil client</strong> et <strong>construire la solution adaptée</strong> — en quelques minutes.</p>
        <div class="cta-row">
          <button class="btn btn-primary" type="button" data-open-chat>Discutons de vos besoins</button>
          <a class="btn btn-ghost" href="/#agent-support">Commencer</a>
        </div>
      </div>

      <div class="hero-art reveal" aria-hidden="true">
        <div class="hero-illus" data-hero-slider>
          <?php
          $imagesDir = dirname(__DIR__, 3) . '/assets/images';
          $files = glob($imagesDir . '/*.svg') ?: [];
          sort($files);
          $slides = [];
          foreach ($files as $f) {
            $base = basename($f);
            if (strtolower($base) === 'humain_support.svg') {
              continue;
            }
            $slides[] = $base;
          }
          ?>

          <?php foreach ($slides as $i => $base): ?>
            <img class="hero-svg<?= $i === 0 ? ' is-active' : '' ?>" data-hero-slide src="/assets/images/<?= rawurlencode($base) ?>" alt="" />
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <?= View::partial('partials/agent-section', [
    'id' => 'agent-support',
    'title' => 'Agent IA de Service Client',
    'subtitle' => '(AI Customer Support Agent)',
    'image' => '/assets/images/support_Agent.svg',
    'detailsUrl' => '/agent-support',
    'bullets' => [
      'Répond aux emails, chats, WhatsApp',
      'Gère les demandes clients 24/7',
      'Résout les cas simples et escalade les cas complexes',
      'Réduit les coûts support et protège la relation client',
    ],
  ]) ?>

  <?= View::partial('partials/agent-section', [
    'id' => 'agent-scheduling',
    'title' => 'Agent IA de Prise de Rendez-vous',
    'subtitle' => '(AI Appointment Scheduling Agent)',
    'image' => '/assets/images/book_metting.svg',
    'detailsUrl' => '/agent-scheduling',
    'bullets' => [
      'Qualifie les demandes entrantes',
      'Propose et confirme des créneaux automatiquement',
      'Envoie rappels et reprogrammations',
      'Transforme chaque demande en opportunité concrète',
    ],
  ]) ?>

  <?= View::partial('partials/agent-section', [
    'id' => 'agent-prospecting',
    'title' => 'Agent IA de Prospection & Qualification',
    'subtitle' => '(AI Sales & Prospecting Agent)',
    'image' => '/assets/images/customer_seeker.svg',
    'detailsUrl' => '/agent-prospecting',
    'bullets' => [
      'Identifie les prospects idéaux',
      'Contacte de manière personnalisée (LinkedIn, email)',
      'Qualifie les leads intelligemment',
      'Alimente le pipeline commercial sans spam',
    ],
  ]) ?>

  <section class="page-section cta-final">
    <div class="container">
      <div class="cta-final-content reveal">
        <h2>Prêt à transformer votre entreprise </h2>
        <p class="lead">Discutez avec notre agent Skill pour qualifier votre demande et construire la solution adaptée à vos besoins.</p>
        <div class="cta-row">
          <button class="btn btn-primary" type="button" data-open-chat>Discutons de vos besoins</button>
        </div>
      </div>
    </div>
  </section>
</article>
