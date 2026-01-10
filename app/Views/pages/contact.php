<?php

declare(strict_types=1);

use App\Core\Trans;

use function App\Core\e;

 ?><article>
  <?= $page['body_html'] ?? '' ?>
 
  <?= App\Core\View::partial('partials/icon-strip', ['slug' => $page['slug'] ?? '']) ?>

  <section class="section reveal">
    <div class="container">
      <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= e((string)$error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e((string)$success) ?></div>
      <?php endif; ?>

      <div class="grid-2">
        <div class="card">
          <h2><?= e(Trans::get('ui','send_message')) ?></h2>
          <form method="post" action="/contact/submit" class="form">
            <div class="field"><label>Name*</label><input name="name" required /></div>
            <div class="field"><label>Email*</label><input name="email" type="email" required /></div>
            <div class="field"><label>Company</label><input name="company" /></div>
            <div class="field"><label>Message*</label><textarea name="message" rows="5" required></textarea></div>
            <button class="btn btn-primary" type="submit">Submit</button>
          </form>
        </div>

        <div class="card">
          <h2><?= e(Trans::get('ui','book_call')) ?></h2>
          <form method="post" action="/book/submit" class="form">
            <div class="field"><label>Name*</label><input name="name" required /></div>
            <div class="field"><label>Email*</label><input name="email" type="email" required /></div>
            <div class="field"><label>Company</label><input name="company" /></div>
            <div class="field"><label>Preferred date</label><input name="preferred_date" placeholder="YYYY-MM-DD" /></div>
            <div class="field"><label>Notes</label><textarea name="notes" rows="3"></textarea></div>
            <button class="btn btn-primary" type="submit">Request</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</article>
