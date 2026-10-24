<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Corporate Profile, Heritage & Leadership';
$pageDescription = 'Comprehensive corporate profile of Softrings International (Pvt) Ltd and the Softrings Group of Companies in Sri Lanka — history since 2000, Board of Directors, executive management, and corporate governance.';
$activeNav = 'about';

$charter = get_corporate_charter();
$entities = get_group_entities();
$timeline = get_company_timeline();
$leadership = get_leadership();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-hero">
  <div class="container page-hero__inner">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="index.php">Overview</a>
      <span aria-hidden="true">/</span>
      <span>Corporate Profile</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">Corporate Dossier · Sri Lanka</span>
        <h1>Softrings International (Pvt) Ltd</h1>
        <p class="page-hero__lead">
          Incorporated on <strong>11 September 2009</strong> as a flagship operating company of the <strong>Softrings Group of Companies</strong> (founded 2000), serving Sri Lanka’s telecommunications, mobile retail, and regional distribution sectors with uncompromising integrity.
        </p>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Legal Classification</span>
          <strong>Private Limited Liability (Sri Lanka)</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Date of Commencement</span>
          <strong>11 September 2009</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Group Founding</span>
          <strong>2000 (SLT-Mobitel Authorized Dealer)</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Primary Corridors</span>
          <strong>Colombo, Seethawaka &amp; Kandy Districts</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Official Domain</span>
          <strong><?= e(COMPANY_DOMAIN) ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Vision, Mission & Charter -->
<section class="section section--white">
  <div class="container">
    <div class="vm-grid">
      <article class="vm-panel vm-panel--dark">
        <span class="eyebrow eyebrow--gold">Our Vision</span>
        <h2>National Connectivity Built on Lasting Trust</h2>
        <p><?= e($charter['vision']) ?></p>
      </article>
      <article class="vm-panel vm-panel--bordered">
        <span class="eyebrow">Our Mission</span>
        <h2>Empowering Subscribers, Retailers &amp; Enterprises</h2>
        <p><?= e($charter['mission']) ?></p>
      </article>
    </div>

    <div class="values-ledger">
      <?php foreach ($charter['values'] as $val): ?>
        <div class="value-column">
          <span class="value-column__num"><?= e($val['code']) ?></span>
          <h3><?= e($val['title']) ?></h3>
          <p><?= e($val['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Group Structure Table / Matrix -->
<section class="section section--ivory" id="group-structure">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Conglomerate Architecture</span>
      <h2 class="atr-heading">The Softrings Group of Companies</h2>
      <p class="atr-sub">
        Over the past quarter-century, Softrings structured specialized private limited companies in Sri Lanka to align dedicated operational teams with national telecom carriers, global handset manufacturers, and regional retail territories.
      </p>
    </header>

    <div class="table-responsive">
      <table class="corporate-table">
        <thead>
          <tr>
            <th>Registered Entity</th>
            <th>Commencement</th>
            <th>Strategic Mandate</th>
            <th>Primary Operational Base</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($entities as $ent): ?>
            <tr class="<?= $ent['highlight'] ? 'is-highlighted-row' : '' ?>">
              <td>
                <strong><?= e($ent['name']) ?></strong>
                <?php if ($ent['highlight']): ?>
                  <span class="inline-tag">Profile Subject</span>
                <?php endif; ?>
              </td>
              <td class="mono-cell"><?= e($ent['commenced']) ?></td>
              <td>
                <strong><?= e($ent['role']) ?></strong>
                <p class="table-subtext"><?= e($ent['summary']) ?></p>
              </td>
              <td><?= e($ent['hq']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Detailed Historical Timeline -->
<section class="section section--white" id="timeline">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Chronicle</span>
      <h2 class="atr-heading">26 Years of Telecommunications Milestones</h2>
    </header>

    <div class="vertical-timeline">
      <?php foreach ($timeline as $item): ?>
        <div class="v-timeline-row">
          <div class="v-timeline-year"><?= e($item['year']) ?></div>
          <div class="v-timeline-marker" aria-hidden="true"></div>
          <div class="v-timeline-card">
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['detail']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Board of Directors & Executive Leadership -->
<section class="section section--ivory" id="leadership">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Corporate Governance</span>
      <h2 class="atr-heading">Board of Directors &amp; Executive Management</h2>
      <p class="atr-sub">
        Guided by experienced Sri Lankan business leadership and functional department heads spanning sales distribution, human resources, finance, corporate secretarial compliance, and technical engineering.
      </p>
    </header>

    <div class="directors-grid">
      <?php foreach ($leadership['board'] as $director):
          $initials = '';
          foreach (preg_split('/\s+/', str_replace(['Mr.', 'Mrs.', 'Ms.'], '', $director['name'])) as $part) {
              if ($part !== '' && ctype_alpha($part[0])) {
                  $initials .= strtoupper($part[0]);
              }
          }
          $initials = substr($initials, 0, 2);
      ?>
        <article class="director-card">
          <figure class="director-card__figure">
            <img
              class="director-card__photo"
              src="<?= e($director['photo']) ?>"
              alt="Placeholder portrait for <?= e($director['name']) ?>, <?= e($director['role']) ?> — official photograph to follow"
              width="640"
              height="800"
              loading="lazy"
            >
            <figcaption class="director-card__pending">Photo pending</figcaption>
          </figure>
          <div class="director-card__body">
            <span class="director-card__role"><?= e($director['role']) ?></span>
            <h3><?= e($director['name']) ?></h3>
            <p><?= e($director['bio']) ?></p>
            <a href="mailto:<?= e($director['email']) ?>" class="director-card__mail"><?= e($director['email']) ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="management-directory">
      <h3 class="subheading">Functional Leadership &amp; Governance Pillars</h3>
      <div class="management-grid">
        <?php foreach ($leadership['management'] as $mgr): ?>
          <div class="mgr-item">
            <img
              class="mgr-item__photo"
              src="<?= e($mgr['photo']) ?>"
              alt="Placeholder portrait for <?= e($mgr['name']) ?>, <?= e($mgr['role']) ?> — official photograph to follow"
              width="640"
              height="800"
              loading="lazy"
            >
            <span class="mgr-item__dept"><?= e($mgr['dept']) ?></span>
            <strong class="mgr-item__name"><?= e($mgr['name']) ?></strong>
            <span class="mgr-item__role"><?= e($mgr['role']) ?></span>
            <a href="mailto:<?= e($mgr['email']) ?>" class="mgr-item__email"><?= e($mgr['email']) ?></a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Culture & Community Recognition -->
<section class="section section--white">
  <div class="container culture-banner">
    <div class="culture-banner__text">
      <span class="eyebrow">Corporate Culture &amp; Recognition</span>
      <h2 class="section-title">Award-Winning Dealer Performance &amp; Team Spirit</h2>
      <p class="prose-text">
        Softrings International’s sustained success is powered by annual <strong>Staff Conventions</strong>, inter-branch <strong>Sports Days</strong>, campus &amp; community roadshows (including University of Moratuwa promotions and Hanwella Shakthi roadshows), and consistent recognition at <strong>SLT-Mobitel Dealer Awards Nights</strong>.
      </p>
      <div class="hero-actions">
        <a href="careers.php" class="btn btn--primary">Join Our Team</a>
        <a href="contact.php" class="btn btn--secondary">Contact Corporate Secretary / Desk</a>
      </div>
    </div>
    <div class="culture-highlights">
      <div class="culture-stat-box">
        <strong>SLT-Mobitel</strong>
        <span>Authorized Regional &amp; Retail Partner</span>
      </div>
      <div class="culture-stat-box">
        <strong>TRCSL Standards</strong>
        <span>100% Genuine Network-Approved Devices</span>
      </div>
      <div class="culture-stat-box">
        <strong>mCash &amp; mPOS</strong>
        <span>Digital Financial Inclusion Across Towns</span>
      </div>
      <div class="culture-stat-box">
        <strong>In-House Lab</strong>
        <span>Certified Hardware &amp; Warranty Technicians</span>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
