<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

http_response_code(404);

$pageTitle = 'Page Not Found';
$pageDescription = 'The requested page could not be located. Return to the Softrings International corporate overview, branch network, business divisions or contact desk.';
$activeNav = '';

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container page-hero__inner">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="index.php">Overview</a>
      <span aria-hidden="true">/</span>
      <span>Page Not Found</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">Error 404</span>
        <h1>That page is not on our network</h1>
        <p class="page-hero__lead">
          The address you requested does not exist, has moved, or was mistyped. Our corporate desk is still reachable — use any of the verified routes below.
        </p>
        <div class="footprint-cta-row" style="margin-top:24px;">
          <a href="index.php" class="btn btn--primary">Return to Corporate Overview</a>
          <a href="contact.php" class="btn btn--secondary">Contact the Corporate Desk</a>
        </div>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Colombo Corporate Office</span>
          <strong>No. 65/10, Piliyandala Road, Maharagama</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Central Province Office</span>
          <strong>No. 102, Nuwara Eliya Road, Gampola</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Corporate Hotline</span>
          <strong><a href="tel:<?= e(COMPANY_HOTLINE_RAW) ?>"><?= e(COMPANY_HOTLINE) ?></a></strong>
        </div>
        <div class="dossier-meta-row">
          <span>General Correspondence</span>
          <strong><a href="mailto:<?= e(COMPANY_PRIMARY_EMAIL) ?>"><?= e(COMPANY_PRIMARY_EMAIL) ?></a></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <span class="eyebrow">Navigate the Corporate Profile</span>
    <h2 class="section-title">Verified Destinations</h2>
    <div class="matrix-grid" style="margin-top:24px;">
      <a class="matrix-box" href="about.php">
        <h3>Corporate Profile</h3>
        <p>Group heritage since 2000, the four subsidiary companies, Board of Directors and executive management.</p>
      </a>
      <a class="matrix-box" href="services.php">
        <h3>Divisions &amp; mPOS</h3>
        <p>SLT-Mobitel dealer services, multi-brand handset distribution, Softrings mPOS terminals and service engineering.</p>
      </a>
      <a class="matrix-box" href="branches.php">
        <h3>Branch Network</h3>
        <p>Eight flagship centers across the Western and Central Provinces with live Sri Lanka Standard Time opening status.</p>
      </a>
      <a class="matrix-box" href="careers.php">
        <h3>Careers</h3>
        <p>Current vacancies across Colombo and Kandy districts, with a verifiable HR application reference system.</p>
      </a>
      <a class="matrix-box" href="contact.php">
        <h3>Partner &amp; Contact</h3>
        <p>Corporate, wholesale and sub-dealer inquiry desk plus live reference tracking for existing submissions.</p>
      </a>
      <a class="matrix-box" href="index.php">
        <h3>Executive Overview</h3>
        <p>The verified corporate registration ledger, operating footprint and group architecture at a glance.</p>
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
