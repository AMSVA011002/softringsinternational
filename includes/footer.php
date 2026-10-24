<?php
declare(strict_types=1);
$groupEntities = get_group_entities();
?>
  </main>

  <!-- Floating Scroll-to-Top Control -->
  <button
    type="button"
    class="scroll-top"
    id="scroll-top"
    aria-label="Scroll back to the top of the page"
    title="Back to top"
    hidden
  >
    <span class="scroll-top__disc" aria-hidden="true"></span>
    <svg class="scroll-top__icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
         stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
      <path d="M12 19V5"/>
      <path d="M5.5 11.5 12 5l6.5 6.5"/>
    </svg>
    <span class="scroll-top__label">Top</span>
  </button>

  <!-- Reference Status Lookup Modal -->
  <div class="modal-backdrop" id="tracker-modal" hidden aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="tracker-modal-title">
      <div class="modal-card__head">
        <div>
          <span class="eyebrow">Real-Time Corporate Desk Lookup</span>
          <h3 id="tracker-modal-title">Track Inquiry or HR Reference</h3>
        </div>
        <button type="button" class="modal-close" data-close-tracker aria-label="Close lookup dialog">&times;</button>
      </div>
      <p class="modal-card__desc">
        Enter the reference code issued when you submitted a dealer/corporate inquiry (e.g. <code>SFT-2026-XXXXX</code>) or career application (<code>HR-SFT-XXXXX</code>).
      </p>
      <form id="tracker-form" class="tracker-form" novalidate>
        <label for="tracker-ref-input" class="sr-only">Reference Number</label>
        <input
          type="text"
          id="tracker-ref-input"
          name="ref"
          placeholder="e.g. SFT-2026-A1B2C"
          required
          autocomplete="off"
        >
        <button type="submit" class="btn btn--primary">Verify Status</button>
      </form>
      <div id="tracker-result" class="tracker-result" aria-live="polite"></div>
    </div>
  </div>

  <!-- Executive Corporate Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-brand-col">
          <div class="brand brand--inverse">
            <span class="brand__emblem" aria-hidden="true">
              <svg viewBox="0 0 48 48" width="40" height="40" fill="none">
                <rect width="48" height="48" rx="11" fill="#002E87"/>
                <circle cx="24" cy="24" r="16" stroke="#8CD8FF" stroke-width="2.6" stroke-dasharray="72 28" stroke-linecap="round"/>
                <circle cx="24" cy="24" r="10.5" stroke="#3AC22A" stroke-width="2.2" stroke-dasharray="46 20" stroke-dashoffset="-14" stroke-linecap="round"/>
                <path d="M27.4 18.2C26.4 17.3 25.0 16.9 23.4 16.9C20.9 16.9 19.3 18.2 19.3 20.0C19.3 21.9 21.1 22.6 23.7 23.3C26.8 24.1 29.1 25.2 29.1 28.0C29.1 30.8 26.6 32.5 23.3 32.5C20.8 32.5 18.9 31.6 17.8 30.2" stroke="#FFFFFF" stroke-width="2.7" stroke-linecap="round"/>
                <circle cx="35" cy="13" r="2.6" fill="#3AC22A"/>
              </svg>
            </span>
            <span class="brand__text">
              <span class="brand__title">Softrings International (Pvt) Ltd</span>
              <span class="brand__subtitle">Member of the Softrings Group of Companies · Sri Lanka</span>
            </span>
          </div>
          <p class="footer-summary">
            Incorporated on 11 September 2009 within the Softrings Group (founded 2000). Authorized Dealer for Sri Lanka Telecom Mobitel, island-wide multi-brand mobile handset &amp; accessory distributor, and smart mPOS merchant solution provider.
          </p>
          <div class="footer-actions">
            <button type="button" class="btn btn--outline-light btn--sm" onclick="window.print()">
              Print / Save Corporate Dossier (PDF)
            </button>
            <button type="button" class="btn btn--ghost-light btn--sm" data-open-tracker>
              Check Reference #
            </button>
          </div>
        </div>

        <div class="footer-col">
          <h4 class="footer-heading">Corporate Navigation</h4>
          <ul class="footer-links">
            <li><a href="index.php">Executive Overview</a></li>
            <li><a href="about.php">Group Heritage &amp; Leadership</a></li>
            <li><a href="services.php">Telecom, Handsets &amp; mPOS</a></li>
            <li><a href="branches.php">Island-Wide Branch Locator</a></li>
            <li><a href="careers.php">Careers — The Human Factor</a></li>
            <li><a href="contact.php">Dealer &amp; Corporate Inquiries</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4 class="footer-heading">Softrings Group Entities</h4>
          <ul class="footer-links footer-links--entities">
            <?php foreach ($groupEntities as $entity): ?>
              <li>
                <strong><?= e($entity['name']) ?></strong>
                <span><?= e($entity['commenced']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="footer-col">
          <h4 class="footer-heading">Key Corporate Offices</h4>
          <address class="footer-address">
            <div class="footer-office">
              <strong>Colombo Corporate Office</strong>
              <span>No. 65/10, Piliyandala Road, Maharagama 10280</span>
              <span class="footer-phone-line"><a href="tel:+94717000090">+94 71 700 0090</a><span class="footer-dot" aria-hidden="true">·</span><a href="tel:+94718728635">+94 71 872 8635</a></span>
            </div>
            <div class="footer-office">
              <strong>Central Province Corporate Office</strong>
              <span>No. 102, Nuwara Eliya Road, Gampola 20500</span>
              <span class="footer-phone-line"><a href="tel:+94714041369">+94 71 404 1369</a><span class="footer-dot" aria-hidden="true">·</span><a href="tel:+94812351212">+94 81 235 1212</a></span>
            </div>
            <div class="footer-office">
              <strong>Kandy &amp; Homagama Hubs</strong>
              <span>Kandy: <a href="tel:+94812227417">+94 81 222 7417</a> · Homagama: <a href="tel:+94112895061">+94 11 289 5061</a></span>
              <a href="mailto:<?= e(COMPANY_PRIMARY_EMAIL) ?>"><?= e(COMPANY_PRIMARY_EMAIL) ?></a>
            </div>
          </address>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= e(COMPANY_NAME) ?>. All Rights Reserved. Democratic Socialist Republic of Sri Lanka.</p>
        <p class="footer-compliance">TRCSL-Compliant Handsets · Official Authorized SLT-Mobitel Dealer Network · <?= e(COMPANY_DOMAIN) ?></p>
      </div>
    </div>
  </footer>

  <script src="assets/js/main.js" defer></script>
</body>
</html>
