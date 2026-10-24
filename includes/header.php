<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . COMPANY_NAME : COMPANY_NAME . ' — Authorized SLT-Mobitel Dealer & Mobile Distribution, Sri Lanka';
$pageDescription = $pageDescription ?? 'Official corporate profile of Softrings International (Pvt) Ltd, part of the Softrings Group of Companies (Est. 2000) in Sri Lanka — Authorized SLT-Mobitel Dealer, multi-brand smartphone distributor, and Softrings mPOS terminal provider across Colombo, Maharagama, Homagama, Kandy, and Gampola.';
$activeNav = $activeNav ?? 'home';

$branchesAll = get_branches();
$openCount = 0;
$scheduleCompact = [];
foreach ($branchesAll as $b) {
    if (get_branch_live_status($b)['is_open']) {
        $openCount++;
    }
    $scheduleCompact[] = [
        'id' => $b['id'],
        'mf' => $b['hours_mf'],
        'sat' => $b['hours_sat'],
        'sun' => $b['hours_sun'],
    ];
}
$trilingual = get_trilingual_motto();
$colomboNowIso = date('c');
$colomboTimeFormatted = date('h:i A');
?>
<!DOCTYPE html>
<html lang="en" data-lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <meta name="theme-color" content="#002E87">
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Corporation",
    "name": "Softrings International (Pvt) Ltd",
    "alternateName": "Softrings Group of Companies",
    "foundingDate": "2009-09-11",
    "slogan": "Your Partner in Technology",
    "url": "https://www.softrings.lk",
    "telephone": "+94-71-700-0090",
    "email": "international@softrings.lk",
    "address": [
      {
        "@type": "PostalAddress",
        "streetAddress": "No. 65/10, Piliyandala Road",
        "addressLocality": "Maharagama",
        "postalCode": "10280",
        "addressRegion": "Western Province",
        "addressCountry": "LK"
      },
      {
        "@type": "PostalAddress",
        "streetAddress": "No. 102, Nuwara Eliya Road",
        "addressLocality": "Gampola",
        "postalCode": "20500",
        "addressRegion": "Central Province",
        "addressCountry": "LK"
      }
    ],
    "parentOrganization": {
      "@type": "Organization",
      "name": "Softrings Group of Companies",
      "foundingDate": "2000"
    }
  }
  </script>
</head>
<body>
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Top Corporate Telemetry & Trilingual Bar -->
  <div class="utility-bar" role="region" aria-label="Corporate status and language">
    <div class="container utility-bar__inner">
      <div class="utility-bar__left">
        <span class="live-indicator <?= $openCount > 0 ? 'is-open' : 'is-standby' ?>" id="live-indicator-badge">
          <span class="live-indicator__dot" aria-hidden="true"></span>
          <span id="slst-clock" data-server-iso="<?= e($colomboNowIso) ?>">Colombo <?= e($colomboTimeFormatted) ?> SLST</span>
        </span>
        <span class="utility-sep" aria-hidden="true">·</span>
        <span class="utility-status" id="utility-open-summary" data-schedules="<?= e((string) json_encode($scheduleCompact)) ?>">
          <?php if ($openCount > 0): ?>
            <strong><?= $openCount ?> of <?= count($branchesAll) ?></strong> Regional Hubs Open Now
          <?php else: ?>
            Standard Hours Resume 8:00 AM SLST · Digital Inquiry Desk Active
          <?php endif; ?>
        </span>
      </div>

      <div class="utility-bar__right">
        <div class="lang-switcher" role="group" aria-label="Corporate motto language">
          <?php foreach ($trilingual as $code => $langData): ?>
            <button
              type="button"
              class="lang-btn <?= $code === 'en' ? 'is-active' : '' ?>"
              data-lang-switch="<?= e($code) ?>"
              data-motto="<?= e($langData['motto']) ?>"
              data-corp-name="<?= e($langData['name']) ?>"
              data-badge="<?= e($langData['badge']) ?>"
              aria-pressed="<?= $code === 'en' ? 'true' : 'false' ?>"
            ><?= e($langData['label']) ?></button>
          <?php endforeach; ?>
        </div>
        <span class="utility-sep hide-sm" aria-hidden="true">|</span>
        <button type="button" class="utility-link-btn" data-open-tracker>
          Track Ref #
        </button>
        <span class="utility-sep hide-sm" aria-hidden="true">|</span>
        <a href="tel:<?= e(COMPANY_HOTLINE_RAW) ?>" class="utility-phone hide-sm"><?= e(COMPANY_HOTLINE) ?></a>
      </div>
    </div>
  </div>

  <!-- Trilingual Dynamic Identity Ribbon -->
  <div class="identity-ribbon">
    <div class="container identity-ribbon__inner">
      <span class="identity-ribbon__badge" id="ribbon-badge"><?= e($trilingual['en']['badge']) ?></span>
      <p class="identity-ribbon__text" id="ribbon-motto"><?= e($trilingual['en']['motto']) ?></p>
    </div>
  </div>

  <!-- Primary Corporate Navigation -->
  <header class="site-header" id="site-header">
    <div class="container site-header__inner">
      <a href="index.php" class="brand" aria-label="Softrings International (Pvt) Ltd Home">
        <span class="brand__emblem" aria-hidden="true">
          <svg viewBox="0 0 48 48" width="42" height="42" fill="none">
            <rect width="48" height="48" rx="11" fill="#002E87"/>
            <circle cx="24" cy="24" r="16" stroke="#8CD8FF" stroke-width="2.6" stroke-dasharray="72 28" stroke-linecap="round"/>
            <circle cx="24" cy="24" r="10.5" stroke="#3AC22A" stroke-width="2.2" stroke-dasharray="46 20" stroke-dashoffset="-14" stroke-linecap="round"/>
            <path d="M27.4 18.2C26.4 17.3 25.0 16.9 23.4 16.9C20.9 16.9 19.3 18.2 19.3 20.0C19.3 21.9 21.1 22.6 23.7 23.3C26.8 24.1 29.1 25.2 29.1 28.0C29.1 30.8 26.6 32.5 23.3 32.5C20.8 32.5 18.9 31.6 17.8 30.2" stroke="#FFFFFF" stroke-width="2.7" stroke-linecap="round"/>
            <circle cx="35" cy="13" r="2.6" fill="#3AC22A"/>
          </svg>
        </span>
        <span class="brand__text">
          <span class="brand__title" id="brand-corp-name">Softrings International</span>
          <span class="brand__subtitle">(Pvt) Ltd · Sri Lanka · Est. 2000 Group</span>
        </span>
      </a>

      <button
        type="button"
        class="nav-toggle"
        id="nav-toggle"
        aria-expanded="false"
        aria-controls="primary-nav"
        aria-label="Toggle corporate navigation"
      >
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
      </button>

      <nav class="primary-nav" id="primary-nav" aria-label="Primary">
        <ul class="primary-nav__list">
          <li><a href="index.php" class="nav-link <?= $activeNav === 'home' ? 'is-active' : '' ?>">Overview</a></li>
          <li><a href="about.php" class="nav-link <?= $activeNav === 'about' ? 'is-active' : '' ?>">Corporate Profile</a></li>
          <li><a href="services.php" class="nav-link <?= $activeNav === 'services' ? 'is-active' : '' ?>">Divisions &amp; mPOS</a></li>
          <li><a href="branches.php" class="nav-link <?= $activeNav === 'branches' ? 'is-active' : '' ?>">Branch Network</a></li>
          <li><a href="careers.php" class="nav-link <?= $activeNav === 'careers' ? 'is-active' : '' ?>">Careers</a></li>
        </ul>
        <div class="primary-nav__cta">
          <a href="contact.php" class="btn btn--primary btn--sm <?= $activeNav === 'contact' ? 'is-current' : '' ?>">Partner &amp; Contact</a>
        </div>
      </nav>
    </div>
  </header>

  <main id="main-content">
