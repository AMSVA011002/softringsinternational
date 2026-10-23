<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Corporate Profile & Authorized Telecom Distribution';
$activeNav = 'home';

$charter = get_corporate_charter();
$services = get_services_catalog();
$branches = get_branches();
$entities = get_group_entities();
$timeline = get_company_timeline();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Masthead: Asymmetric Executive Split -->
<section class="hero-masthead">
  <div class="container hero-masthead__grid">
    <div class="hero-masthead__content">
      <div class="eyebrow-pill">
        <span class="eyebrow-pill__dot" aria-hidden="true"></span>
        <span>Softrings Group Heritage Since 2000 · Incorporated 2009</span>
      </div>

      <h1 class="hero-title">
        Connecting Sri Lanka Through <span class="serif-accent">Trusted Telecom</span> &amp; Genuine Mobile Distribution.
      </h1>

      <p class="hero-lead">
        <strong>Softrings International (Pvt) Ltd</strong> is a premier Sri Lankan telecommunications and mobile handset enterprise — operating Authorized <strong>SLT-Mobitel</strong> showrooms, island-wide multi-brand device wholesale, and <strong>Softrings mPOS</strong> retail payment terminals across Western and Central Provinces.
      </p>

      <div class="hero-actions">
        <a href="about.php" class="btn btn--primary btn--lg">Explore Corporate Profile</a>
        <a href="branches.php" class="btn btn--outline-light btn--lg">Locate Regional Hubs (8)</a>
      </div>

      <dl class="hero-kpis" aria-label="Key Corporate Indicators">
        <div class="hero-kpi">
          <dt>Group Legacy</dt>
          <dd><?= (int) date('Y') - COMPANY_FOUNDED_GROUP ?>+ Yrs</dd>
        </div>
        <div class="hero-kpi">
          <dt>Core Showrooms &amp; Hubs</dt>
          <dd>08 Centers</dd>
        </div>
        <div class="hero-kpi">
          <dt>Group Subsidiaries</dt>
          <dd>04 Entities</dd>
        </div>
        <div class="hero-kpi">
          <dt>Authorized Partner</dt>
          <dd>SLT-Mobitel</dd>
        </div>
      </dl>
    </div>

    <div class="hero-masthead__visual">
      <figure class="hero-media-frame">
        <img
          src="assets/images/hero-corporate.jpg"
          alt="Softrings International flagship mobile showroom and customer service center in Colombo, Sri Lanka"
          width="1280"
          height="720"
          fetchpriority="high"
        >
        <figcaption class="hero-media-caption">
          <div>
            <strong>Colombo &amp; Hill Country Dual Presence</strong>
            <span>Maharagama · Homagama · Nugegoda · Moratuwa · Avissawella · Kandy · Gampola</span>
          </div>
          <span class="caption-tag">TRCSL Approved</span>
        </figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- Corporate Fact Ledger Strip -->
<section class="fact-ledger" aria-label="Verified Corporate Registration Summary">
  <div class="container fact-ledger__grid">
    <div class="fact-ledger__item">
      <span class="fact-ledger__label">Legal Entity</span>
      <strong class="fact-ledger__val">Softrings International (Pvt) Ltd</strong>
      <span class="fact-ledger__meta">Commenced 11 September 2009</span>
    </div>
    <div class="fact-ledger__item">
      <span class="fact-ledger__label">Colombo Corporate Office</span>
      <strong class="fact-ledger__val">No. 65/10, Piliyandala Rd, Maharagama</strong>
      <span class="fact-ledger__meta">Western Province · 10280 · +94 71 700 0090</span>
    </div>
    <div class="fact-ledger__item">
      <span class="fact-ledger__label">Central Corporate Office</span>
      <strong class="fact-ledger__val">No. 102, Nuwara Eliya Rd, Gampola</strong>
      <span class="fact-ledger__meta">Central Province · 20500 · +94 71 404 1369</span>
    </div>
    <div class="fact-ledger__item">
      <span class="fact-ledger__label">Chairman &amp; Governance</span>
      <strong class="fact-ledger__val">Mr. P. Amal Nishantha Silva</strong>
      <span class="fact-ledger__meta">Softrings Group of Companies</span>
    </div>
  </div>
</section>

<!-- Editorial Charter & Story Section -->
<section class="section section--ivory">
  <div class="container editorial-split">
    <div class="editorial-split__left">
      <span class="eyebrow">Who We Are</span>
      <h2 class="section-title">Over Two Decades of Integrity in Sri Lanka’s Mobile Ecosystem</h2>
      <blockquote class="charter-quote">
        &ldquo;<?= e($charter['quote']) ?>&rdquo;
        <footer>— Official Corporate Charter, Softrings International (Pvt) Ltd</footer>
      </blockquote>
      <p class="prose-text">
        Originating in <strong>2000</strong> as a trusted Authorized Dealer for <strong>Sri Lanka Telecom Mobitel</strong>, the Softrings Group expanded steadily to bridge urban Colombo commercial corridors with the Central Province hill country. Incorporated on <strong>11 September 2009</strong>, <strong>Softrings International (Pvt) Ltd</strong> anchors the Group’s multi-brand handset retail, original accessories supply, corporate device procurement, and showroom operations.
      </p>
      <div class="inline-links">
        <a href="about.php" class="text-arrow-link">Read Full Group History &amp; Leadership &rarr;</a>
        <a href="services.php#mpos-calculator" class="text-arrow-link">Explore Softrings mPOS Merchant Network &rarr;</a>
      </div>
    </div>

    <div class="editorial-split__right">
      <div class="subsidiary-stack">
        <div class="subsidiary-stack__header">
          <span>The Softrings Group Architecture</span>
          <span>4 Operating Companies</span>
        </div>
        <?php foreach ($entities as $ent): ?>
          <article class="entity-row <?= $ent['highlight'] ? 'entity-row--primary' : '' ?>">
            <div class="entity-row__top">
              <h3><?= e($ent['name']) ?></h3>
              <span class="mono-badge"><?= e($ent['commenced']) ?></span>
            </div>
            <p class="entity-row__role"><?= e($ent['role']) ?></p>
            <p class="entity-row__hq"><?= e($ent['hq']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Core Business Verticals (Editorial Ledger + Feature Visual) -->
<section class="section section--white">
  <div class="container">
    <div class="section-header-row">
      <div>
        <span class="eyebrow">Core Business Verticals</span>
        <h2 class="section-title">Integrated Telecom, Device &amp; FinTech Infrastructure</h2>
      </div>
      <a href="services.php" class="btn btn--secondary">View All Divisions</a>
    </div>

    <div class="divisions-showcase">
      <div class="divisions-showcase__list">
        <?php foreach ($services as $idx => $srv): ?>
          <article class="division-item">
            <div class="division-item__index"><?= e($srv['code']) ?></div>
            <div class="division-item__body">
              <div class="division-item__title-row">
                <h3><?= e($srv['title']) ?></h3>
                <span class="division-metric"><?= e($srv['metrics']) ?></span>
              </div>
              <p><?= e($srv['subtitle']) ?></p>
              <ul class="compact-check-list">
                <?php foreach (array_slice($srv['items'], 0, 2) as $bullet): ?>
                  <li><?= e($bullet) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <aside class="divisions-showcase__aside">
        <figure class="aside-card">
          <img
            src="assets/images/distribution-network.jpg"
            alt="Softrings distribution executives inspecting wholesale mobile inventory and wireless mPOS payment terminals"
            width="900"
            height="675"
            loading="lazy"
          >
          <div class="aside-card__body">
            <span class="eyebrow">Merchant &amp; Sub-Dealer Ecosystem</span>
            <h3>National Brand &amp; mPOS Distribution</h3>
            <p>
              With proven heritage as National Distributor for <strong>Alcatel OneTouch</strong> and multi-brand supply across Samsung, Nokia, Huawei, and Xiaomi, our fleet serves retail communication shops from Moratuwa to Gampola.
            </p>
            <a href="contact.php?type=Sub-Dealer+%2F+mPOS+Onboarding" class="btn btn--primary btn--full">Apply for Dealer / mPOS Account</a>
          </div>
        </figure>
      </aside>
    </div>
  </div>
</section>

<!-- Regional Footprint Highlight: Colombo to Central Hill Country -->
<section class="section section--navy">
  <div class="container footprint-highlight">
    <div class="footprint-highlight__media">
      <img
        src="assets/images/central-hill-country-hub.jpg"
        alt="Telecommunications corridor spanning Kandy and Gampola in Sri Lanka's Central Province"
        width="1280"
        height="720"
        loading="lazy"
      >
      <div class="footprint-overlay-stat">
        <span class="footprint-overlay-stat__num">Western &amp; Central</span>
        <span class="footprint-overlay-stat__txt">Authorized SLT-Mobitel Distribution Corridors across Colombo, Seethawaka &amp; Kandy Districts</span>
      </div>
    </div>

    <div class="footprint-highlight__info">
      <span class="eyebrow eyebrow--gold">Island-Wide Operational Reach</span>
      <h2 class="section-title section-title--light">8 Flagship Centers &amp; Live Branch Telemetry</h2>
      <p class="prose-text prose-text--light">
        Whether settling an SLT-Mobitel bill in <strong>Homagama</strong>, registering an Upahara broadband package in <strong>Maharagama</strong>, or sourcing wholesale smartphones in <strong>Kandy</strong> and <strong>Gampola</strong>, our regional centers deliver direct company-backed service.
      </p>

      <div class="mini-branch-grid">
        <?php foreach (array_slice($branches, 0, 6) as $b):
            $st = get_branch_live_status($b);
        ?>
          <a href="branches.php#<?= e($b['id']) ?>" class="mini-branch-card">
            <div class="mini-branch-card__top">
              <strong><?= e($b['city']) ?></strong>
              <span class="status-pill <?= $st['is_open'] ? 'is-open' : 'is-closed' ?>" data-mini-status="<?= e($b['id']) ?>">
                <?= $st['is_open'] ? 'Open' : 'Closed' ?>
              </span>
            </div>
            <span class="mini-branch-card__type"><?= e($b['type']) ?></span>
            <span class="mini-branch-card__tel"><?= e($b['phones'][0]) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="footprint-cta-row">
        <a href="branches.php" class="btn btn--gold">Open Interactive Branch Map</a>
        <a href="tel:+94717000090" class="btn btn--outline-light">Call Colombo Desk: +94 71 700 0090</a>
      </div>
    </div>
  </div>
</section>

<!-- Compact Timeline Ribbon -->
<section class="section section--ivory">
  <div class="container">
    <div class="section-header-row">
      <div>
        <span class="eyebrow">Corporate Trajectory</span>
        <h2 class="section-title">Milestones of Growth (2000 – <?= date('Y') ?>)</h2>
      </div>
      <a href="about.php#timeline" class="text-arrow-link">Full Historical Archive &rarr;</a>
    </div>

    <div class="timeline-strip">
      <?php foreach ($timeline as $step): ?>
        <div class="timeline-step">
          <span class="timeline-step__year"><?= e($step['year']) ?></span>
          <h3 class="timeline-step__title"><?= e($step['title']) ?></h3>
          <p class="timeline-step__desc"><?= e($step['detail']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Executive Conversion Band -->
<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <span class="eyebrow">Corporate, Wholesale &amp; Retail Partnerships</span>
      <h2>Ready to Partner with Softrings International?</h2>
      <p>Connect with our Maharagama or Gampola corporate desks for bulk corporate handsets, SLT-Mobitel enterprise connections, or retail mPOS terminal onboarding.</p>
    </div>
    <div class="cta-band__buttons">
      <a href="contact.php" class="btn btn--primary btn--lg">Submit Corporate Inquiry</a>
      <a href="careers.php" class="btn btn--secondary btn--lg">Explore Careers</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
