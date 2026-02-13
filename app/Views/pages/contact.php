<?php

declare(strict_types=1);

use App\Core\Trans;

use function App\Core\e;

 ?><article class="page-section">
  <div class="container">
    <a href="javascript:history.back()" class="btn-back" title="Retour">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
    </a>

    <div class="contact-hero">
      <img src="/assets/images/humain_support.svg" alt="Support humain" class="contact-image" />
      
      <div class="contact-buttons">
        <a href="https://wa.me/243993900488" target="_blank" rel="noopener" class="btn-contact btn-whatsapp">
          <img src="/assets/images/whatsapp.png" alt="WhatsApp" class="contact-icon" />
          <span>Contactez-nous sur WhatsApp</span>
        </a>

        <a href="mailto:contact.nestcorp@gmail.com" class="btn-contact btn-email">
          <img src="/assets/images/google.png" alt="Email" class="contact-icon" />
          <span>Nous envoyer un mail</span>
        </a>
      </div>
    </div>
  </div>
</article>
