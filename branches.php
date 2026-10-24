<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/sri-lanka-districts.php';

$pageTitle = 'Island-Wide Branch & Corporate Office Network';
$pageDescription = 'Locate Softrings International (Pvt) Ltd corporate offices and Authorized SLT-Mobitel showrooms in Maharagama, Gampola, Homagama, Kandy, Avissawella, Moratuwa, and Nugegoda, Sri Lanka.';
$activeNav = 'branches';

$branches = get_branches();
$extended = get_extended_touchpoints();
$districts = get_sri_lanka_districts();

// Provinces where Softrings operates, and the districts that hold a branch —
// both are used to tint the map so the network reads at a glance.
$activeProvinces = ['Western', 'Central'];
$branchDistricts = ['colombo', 'kandy'];
$cityLabels = [
    ['Maharagama', 6.8448, 79.9285],
    ['Kandy', 7.2955, 80.6378],
    ['Gampola', 7.1644, 80.5766],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container page-hero__inner">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="index.php">Overview</a>
      <span aria-hidden="true">/</span>
      <span>Branch Network</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">Island-Wide Presence</span>
        <h1>Corporate Offices &amp; Authorized Showrooms</h1>
        <p class="page-hero__lead">
          Strategically positioned across Sri Lanka’s High Level Road commercial corridor, coastal Moratuwa, Seethawaka (Avissawella), and the Central Province hubs of Kandy and Gampola.
        </p>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Colombo Corporate Office</span>
          <strong>No. 65/10, Piliyandala Rd, Maharagama</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Central Corporate Office</span>
          <strong>No. 102, Nuwara Eliya Rd, Gampola</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Live Timezone</span>
          <strong>Asia/Colombo (SLST · UTC+05:30)</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Current Local Time</span>
          <strong id="branches-local-time"><?= e(date('l, h:i A')) ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Interactive Map + Filterable Branch Directory -->
<section class="section section--white">
  <div class="container">
    <div class="branch-controls" role="search" aria-label="Filter Softrings branches">
      <div class="filter-pills" role="group" aria-label="Filter by province">
        <button type="button" class="filter-pill is-active" data-province-filter="all">All Centers (<?= count($branches) ?>)</button>
        <button type="button" class="filter-pill" data-province-filter="Western">Western Province (6)</button>
        <button type="button" class="filter-pill" data-province-filter="Central">Central Province (2)</button>
      </div>

      <div class="branch-search-group">
        <input
          type="search"
          id="branch-search-input"
          placeholder="Search city, road, or service (e.g. Gampola, mCash, Repair)..."
          aria-label="Search branches"
        >
        <label class="open-now-toggle">
          <input type="checkbox" id="branch-open-only">
          <span>Open Now Only</span>
        </label>
      </div>
    </div>

    <div class="branch-explorer">
      <!-- Interactive Sri Lanka District Map -->
      <aside class="srilanka-map-panel" aria-label="Sri Lanka Branch Network Map">
        <div class="srilanka-map-panel__head">
          <strong>Sri Lanka Operational Map</strong>
          <span>Click a node to inspect hub</span>
        </div>
        <div class="srilanka-svg-wrap">
          <svg
            viewBox="0 0 450 793"
            class="srilanka-svg"
            role="img"
            aria-labelledby="sl-map-title sl-map-desc"
          >
            <title id="sl-map-title">Softrings International branch locations across Sri Lanka</title>
            <desc id="sl-map-desc">District map of Sri Lanka. The eight Softrings corporate offices and showrooms are marked with pins in Colombo and Kandy districts, with live open or closed status.</desc>
            <defs>
              <linearGradient id="slLandGrad" x1="0.1" y1="0" x2="0.9" y2="1">
                <stop offset="0%" stop-color="#EEF6EC"/>
                <stop offset="52%" stop-color="#EDF3FB"/>
                <stop offset="100%" stop-color="#DFEBF8"/>
              </linearGradient>
              <linearGradient id="slActiveGrad" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#DDF3D6"/>
                <stop offset="100%" stop-color="#CFE6FA"/>
              </linearGradient>
              <filter id="slLandShadow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="5" stdDeviation="6" flood-color="#00306F" flood-opacity="0.2"/>
              </filter>
            </defs>

            <!-- District polygons: real Sri Lanka boundaries -->
            <g class="sl-districts" filter="url(#slLandShadow)">
              <?php foreach ($districts as $district):
                  $isActive = in_array($district['province'], $activeProvinces, true);
                  $hasBranch = in_array($district['id'], $branchDistricts, true);
              ?>
                <path
                  d="<?= e($district['path']) ?>"
                  class="sl-district<?= $isActive ? ' is-active-province' : '' ?><?= $hasBranch ? ' has-branch' : '' ?>"
                  data-district="<?= e($district['id']) ?>"
                  data-district-name="<?= e($district['name']) ?>"
                  data-province="<?= e($district['province']) ?>"
                >
                  <title><?= e($district['name']) ?> District · <?= e($district['province']) ?> Province</title>
                </path>
              <?php endforeach; ?>
            </g>

            <!-- Operational corridor: Western coast to Central highlands -->
            <polyline
              class="sl-corridor"
              points="<?php
                  $corridor = [];
                  foreach (['moratuwa-branch', 'maharagama-hq', 'avissawella-branch', 'gampola-corp', 'kandy-regional'] as $corridorId) {
                      foreach ($branches as $b) {
                          if ($b['id'] === $corridorId) {
                              $pt = sri_lanka_map_point((float) $b['lat'], (float) $b['lng']);
                              $corridor[] = $pt['x'] . ',' . $pt['y'];
                          }
                      }
                  }
                  echo e(implode(' ', $corridor));
              ?>"
            />

            <!-- City labels -->
            <?php foreach ($cityLabels as $label):
                $pt = sri_lanka_map_point($label[1], $label[2]);
            ?>
              <text
                x="<?= $pt['x'] + 12 ?>"
                y="<?= $pt['y'] + 4 ?>"
                class="map-city-label"
              ><?= e($label[0]) ?></text>
            <?php endforeach; ?>

            <!-- Compass -->
            <g class="map-compass" transform="translate(414 58)" aria-hidden="true">
              <circle cx="0" cy="0" r="17"/>
              <path d="M0,-11 L4.5,4 L0,1.4 L-4.5,4 Z"/>
              <text x="0" y="15" text-anchor="middle">N</text>
            </g>

            <!-- Branch pins -->
            <?php foreach ($branches as $b):
                $st = get_branch_live_status($b);
                $pt = sri_lanka_map_point((float) $b['lat'], (float) $b['lng']);
            ?>
              <g
                class="map-pin <?= $st['is_open'] ? 'is-open' : '' ?>"
                data-target-branch="<?= e($b['id']) ?>"
                tabindex="0"
                role="button"
                aria-label="<?= e($b['city'] . ' — ' . $b['name'] . ($st['is_open'] ? ' (open now)' : ' (closed)')) ?>"
              >
                <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="11" class="map-pin__halo"/>
                <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="5" class="map-pin__core"/>
                <circle cx="<?= $pt['x'] ?>" cy="<?= $pt['y'] ?>" r="1.6" class="map-pin__dot"/>
              </g>
            <?php endforeach; ?>
          </svg>
        </div>
        <p class="srilanka-map-panel__count">
          <strong><?= count($branches) ?></strong> centers across <?= count($activeProvinces) ?> provinces · <?= count($districts) ?> districts mapped
        </p>
        <div class="map-legend">
          <span><i class="legend-dot legend-dot--blue"></i> Corporate / Regional Center</span>
          <span><i class="legend-dot legend-dot--green"></i> Open Now (SLST)</span>
          <span><i class="legend-swatch"></i> Western &amp; Central Province coverage</span>
        </div>
        <p class="srilanka-map-panel__credit">
          District boundaries: <a href="https://mapsvg.com/maps/sri-lanka" rel="noopener nofollow">MapSVG</a> (CC BY 4.0)
        </p>
      </aside>

      <!-- Branch Cards List -->
      <div class="branch-cards-list" id="branch-cards-list">
        <?php foreach ($branches as $b):
            $st = get_branch_live_status($b);
        ?>
          <article
            class="branch-card"
            id="<?= e($b['id']) ?>"
            data-province="<?= e($b['province']) ?>"
            data-open="<?= $st['is_open'] ? '1' : '0' ?>"
            data-search="<?= e(strtolower($b['name'] . ' ' . $b['city'] . ' ' . $b['address'] . ' ' . implode(' ', $b['services']))) ?>"
          >
            <div class="branch-card__top">
              <div>
                <span class="branch-card__badge"><?= e($b['type']) ?> · <?= e($b['province']) ?> Province</span>
                <h2><?= e($b['name']) ?></h2>
              </div>
              <span class="status-pill <?= $st['is_open'] ? 'is-open' : 'is-closed' ?>" data-full-status="<?= e($b['id']) ?>">
                <?= e($st['label']) ?>
              </span>
            </div>

            <p class="branch-card__address">
              <strong>Address:</strong> <?= e($b['address']) ?> <span class="mono-inline">(Postal <?= e($b['postal']) ?> · <?= e($b['coords']) ?>)</span>
            </p>

            <div class="branch-card__details">
              <div>
                <span class="detail-k">Telephone Lines</span>
                <div class="phone-links">
                  <?php foreach ($b['phones'] as $ph): ?>
                    <a href="tel:<?= e(preg_replace('/\s+/', '', $ph)) ?>"><?= e($ph) ?></a>
                  <?php endforeach; ?>
                </div>
              </div>
              <div>
                <span class="detail-k">Operating Hours</span>
                <span class="detail-v"><?= e($b['hours_label']) ?></span>
              </div>
            </div>

            <div class="branch-card__tags">
              <?php foreach ($b['services'] as $tag): ?>
                <span class="service-chip"><?= e($tag) ?></span>
              <?php endforeach; ?>
            </div>

            <div class="branch-card__footer">
              <a href="contact.php?branch=<?= urlencode($b['name']) ?>" class="text-arrow-link">Message This Branch &rarr;</a>
              <a href="mailto:<?= e($b['email']) ?>" class="branch-email-link"><?= e($b['email']) ?></a>
            </div>
          </article>
        <?php endforeach; ?>

        <div id="branch-empty-state" class="empty-state" hidden>
          <h3>No matching branches found</h3>
          <p>Try clearing your search filter or switching to "All Centers" to view all 8 regional locations.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Extended Distribution & Satellite Network -->
<section class="section section--ivory">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Extended Territory Network</span>
      <h2 class="atr-heading">Additional Group Touchpoints &amp; Distribution Corridors</h2>
      <p class="atr-sub">
        Alongside our 8 primary corporate and flagship centers, the Softrings Group has established wholesale distribution and retail touchpoints across Greater Colombo, Sabaragamuwa, and the Central Highlands.
      </p>
    </header>

    <div class="table-responsive">
      <table class="corporate-table">
        <thead>
          <tr>
            <th>City / Corridor</th>
            <th>Recorded Group Location</th>
            <th>Direct Line</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($extended as $tp): ?>
            <tr>
              <td><strong><?= e($tp['city']) ?></strong></td>
              <td><?= e($tp['address']) ?></td>
              <td class="mono-cell"><a href="tel:<?= e(preg_replace('/\s+/', '', $tp['phone'])) ?>"><?= e($tp['phone']) ?></a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
