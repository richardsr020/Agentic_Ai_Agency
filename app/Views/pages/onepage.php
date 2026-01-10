<?php

declare(strict_types=1);

use App\Core\View;

?><article>
  <div id="home"></div>

  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy reveal">
        <h1>Des agents IA qui comprennent vos vrais besoins</h1>
        <p class="lead">Discutez avec notre agent (Skill) pour qualifier votre demande, établir votre profil client et construire la solution adaptée — en quelques minutes.</p>
        <div class="cta-row">
          <button class="btn btn-primary" type="button" data-open-chat>Discutons de vos besoins</button>
          <a class="btn btn-ghost" href="/#agent-support">Commencer</a>
        </div>
      </div>

      <div class="hero-art reveal" aria-hidden="true">
        <div class="hero-illus" data-hero-slider>
          <img class="hero-svg is-active" data-hero-slide src="/assets/images/business_growth.svg" alt="" />
          <img class="hero-svg" data-hero-slide src="/assets/images/better_performance.svg" alt="" />
          <img class="hero-svg" data-hero-slide src="/assets/images/setup_AI%20agent.svg" alt="" />
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
    'showBookCall' => true,
    'bullets' => [
      'Identifie les prospects idéaux',
      'Contacte de manière personnalisée (LinkedIn, email)',
      'Qualifie les leads intelligemment',
      'Alimente le pipeline commercial sans spam',
    ],
  ]) ?>
</article>
