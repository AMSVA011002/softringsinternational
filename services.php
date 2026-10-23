<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Business Divisions, Mobile Distribution & mPOS Solutions';
$pageDescription = 'Explore Softrings International (Pvt) Ltd business divisions: Authorized SLT-Mobitel Dealer services, TRCSL-approved multi-brand smartphone distribution, Softrings mPOS retail payment terminals, and technical service centers.';
$activeNav = 'services';

$services = get_services_catalog();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container page-hero__inner">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="index.php">Overview</a>
      <span aria-hidden="true">/</span>
      <span>Divisions &amp; mPOS</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">Commercial Portfolio</span>
        <h1>Telecom Services, Device Wholesale &amp; Softrings mPOS</h1>
        <p class="page-hero__lead">
          Four synergistic operating divisions connecting national carrier services, global handset brands, retail merchant payment terminals, and component-level warranty engineering across Sri Lanka.
        </p>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Carrier Authorization</span>
          <strong>Sri Lanka Telecom Mobitel</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Brand Heritage</span>
          <strong>Samsung · Nokia · Alcatel · Huawei · Xiaomi</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Merchant Platform</span>
          <strong>Softrings mPOS &amp; mCash Retail</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Wholesale &amp; Delivery Desk</span>
          <strong>+94 71 872 8879 / +94 71 700 0090</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Detailed Divisions Stack -->
<section class="section section--white">
  <div class="container">
    <div class="service-blocks">
      <?php foreach ($services as $srv): ?>
        <article class="service-detail-card" id="<?= e($srv['slug']) ?>">
          <div class="service-detail-card__meta">
            <span class="mono-badge"><?= e($srv['code']) ?></span>
            <span class="service-detail-card__metric"><?= e($srv['metrics']) ?></span>
          </div>
          <div class="service-detail-card__content">
            <h2><?= e($srv['title']) ?></h2>
            <p class="service-detail-card__sub"><?= e($srv['subtitle']) ?></p>
            <ul class="feature-check-grid">
              <?php foreach ($srv['items'] as $point): ?>
                <li><?= e($point) ?></li>
              <?php endforeach; ?>
            </ul>
            <div class="service-detail-card__actions">
              <a href="contact.php?type=<?= urlencode($srv['title']) ?>" class="btn btn--primary btn--sm">Inquire About <?= e($srv['code']) ?></a>
              <a href="branches.php" class="text-arrow-link">Find Nearest Service Branch &rarr;</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Interactive Softrings mPOS & Sub-Dealer Estimator -->
<section class="section section--ivory" id="mpos-calculator">
  <div class="container calculator-layout">
    <div class="calculator-layout__intro">
      <span class="eyebrow">Retail Partner Utility</span>
      <h2 class="section-title">Softrings mPOS &amp; Sub-Dealer Margin Estimator</h2>
      <p class="prose-text">
        Communication centers, supermarkets, pharmacies, and regional phone shops across Colombo, Seethawaka, Kandy, and Gampola partner with <strong>Softrings International</strong> to deploy <strong>Softrings mPOS</strong> terminals and authorized wholesale inventory.
      </p>
      <p class="prose-text">
        Use this indicative Sri Lankan Rupee (LKR) calculator to estimate monthly retail partner earnings from daily reload/bill-pay throughput, SIM activations, and handset unit sales.
      </p>
      <ul class="compact-check-list">
        <li>Instant thermal receipt printing for reloads, postpaid &amp; utility bills</li>
        <li>Dedicated field executive support &amp; rapid float replenishment</li>
        <li>Genuine TRCSL-registered smartphones with official dealer margin</li>
      </ul>
    </div>

    <div class="calculator-card" aria-label="Merchant Margin Estimator">
      <div class="calculator-card__header">
        <strong>Indicative Monthly Partner Estimator (LKR)</strong>
        <span class="mono-badge">SLR / LKR</span>
      </div>

      <div class="calc-control">
        <div class="calc-control__top">
          <label for="calc-reloads">Daily mPOS Reload &amp; Bill Volume (LKR)</label>
          <output id="out-reloads" for="calc-reloads">Rs. 35,000 / day</output>
        </div>
        <input type="range" id="calc-reloads" min="5000" max="150000" step="5000" value="35000">
      </div>

      <div class="calc-control">
        <div class="calc-control__top">
          <label for="calc-sims">Monthly SIM &amp; Broadband Activations</label>
          <output id="out-sims" for="calc-sims">45 connections</output>
        </div>
        <input type="range" id="calc-sims" min="5" max="250" step="5" value="45">
      </div>

      <div class="calc-control">
        <div class="calc-control__top">
          <label for="calc-phones">Monthly Handset &amp; Modem Units Sold</label>
          <output id="out-phones" for="calc-phones">18 units</output>
        </div>
        <input type="range" id="calc-phones" min="0" max="100" step="2" value="18">
      </div>

      <div class="calc-summary">
        <div class="calc-summary__row">
          <span>Est. Monthly mPOS Reload &amp; Bill Commission</span>
          <strong id="res-mpos">Rs. 47,250</strong>
        </div>
        <div class="calc-summary__row">
          <span>Est. Activation &amp; Package Incentives</span>
          <strong id="res-sims">Rs. 15,750</strong>
        </div>
        <div class="calc-summary__row">
          <span>Est. Wholesale Device &amp; Accessory Margin</span>
          <strong id="res-devices">Rs. 50,400</strong>
        </div>
        <div class="calc-summary__total">
          <span>Projected Gross Monthly Partner Earnings</span>
          <strong id="res-total">Rs. 113,400</strong>
        </div>
      </div>

      <p class="calc-disclaimer">
        *Illustrative trade estimate based on typical retail reload percentages, connection incentives, and sub-dealer handset margins. Actual commercial terms are confirmed upon territory onboarding.
      </p>

      <a href="contact.php?type=Sub-Dealer+%2F+mPOS+Onboarding" class="btn btn--primary btn--full">
        Request mPOS Terminal &amp; Sub-Dealer Rates
      </a>
    </div>
  </div>
</section>

<!-- Brand & Service Matrix -->
<section class="section section--white">
  <div class="container">
    <div class="section-header-narrow">
      <span class="eyebrow">Authorized Product Matrix</span>
      <h2 class="section-title">What You Can Access at Every Softrings Center</h2>
    </div>

    <div class="matrix-grid">
      <div class="matrix-box">
        <h3>SLT-Mobitel Subscriber Care</h3>
        <p>Prepaid &amp; Postpaid connections, Upahara state-sector packages, 4G/LTE Home Broadband routers, SIM replacements, and instant bill settlement.</p>
      </div>
      <div class="matrix-box">
        <h3>Multi-Brand Handsets</h3>
        <p>Original smartphones and feature phones across Samsung, Nokia, Alcatel, Huawei, Xiaomi, and entry-level dual-SIM devices with official warranty.</p>
      </div>
      <div class="matrix-box">
        <h3>Genuine Accessories</h3>
        <p>Fast chargers, power banks, USB-C/Lightning cables, wireless earbuds, routers/dongles, and protective gear verified for durability.</p>
      </div>
      <div class="matrix-box">
        <h3>Corporate &amp; Home Delivery</h3>
        <p>Direct delivery for corporate device orders, broadband modems, and sub-dealer stock across Colombo, Homagama, Avissawella, Kandy, and Gampola.</p>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
