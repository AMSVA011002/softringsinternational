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

// Every office, showroom and group store, ordered office → showroom → store so
// the map numbers match the printed key beside it.
$mapLocations = get_map_locations();
$categoryOrder = ['office' => 0, 'showroom' => 1, 'store' => 2];
usort($mapLocations, static fn (array $a, array $b): int => $categoryOrder[$a['category']] <=> $categoryOrder[$b['category']]);
$mapPins = layout_map_pins($mapLocations);
$mapById = [];
foreach ($mapPins as $pin) {
    $mapById[$pin['id']] = $pin;
}
$mapCounts = ['office' => 0, 'showroom' => 0, 'store' => 0];
foreach ($mapPins as $pin) {
    $mapCounts[$pin['category']]++;
}
$keyLabels = [
    'office' => 'Corporate Office',
    'showroom' => 'Showroom',
    'store' => 'Group Store',
];
$detailPins = layout_detail_pins($mapPins);
$corridorPoints = [];
foreach (['pettah-store', 'nugegoda-tech', 'maharagama-hq', 'homagama-flagship', 'athurugiriya-store', 'avissawella-branch'] as $corridorId) {
    foreach ($detailPins as $dp) {
        if ($dp['id'] === $corridorId) {
            $corridorPoints[] = sprintf('%.1f,%.1f', $dp['dx'], $dp['dy']);
        }
    }
}

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
          placeholder="Search city, road or service..."
          aria-label="Search branches"
        >
        <label class="open-now-toggle">
          <input type="checkbox" id="branch-open-only">
          <span>Open Now Only</span>
        </label>
      </div>
    </div>

    <!-- Full network map: every office, showroom and group store -->
    <div class="network-map">
      <div class="srilanka-map-panel" id="network-map" data-map-root>
        <div class="srilanka-map-panel__head">
          <strong>Sri Lanka Operational Map</strong>
          <span>Hover or tap a marker</span>
        </div>
        <div class="srilanka-svg-wrap">
          <svg
            viewBox="0 0 450 793"
            class="srilanka-svg"
            role="img"
            aria-labelledby="sl-map-title sl-map-desc"
          >
            <title id="sl-map-title">Softrings International branch locations across Sri Lanka</title>
            <desc id="sl-map-desc">District map of Sri Lanka marking all <?= count($mapPins) ?> Softrings locations — corporate offices, SLT-Mobitel showrooms and group distribution stores — numbered to match the key beside the map.</desc>
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
                      if (isset($mapById[$corridorId])) {
                          $corridor[] = $mapById[$corridorId]['x'] . ',' . $mapById[$corridorId]['y'];
                      }
                  }
                  echo e(implode(' ', $corridor));
              ?>"
            />

            <!-- Compass -->
            <g class="map-compass" transform="translate(414 58)" aria-hidden="true">
              <circle cx="0" cy="0" r="17"/>
              <path d="M0,-11 L4.5,4 L0,1.4 L-4.5,4 Z"/>
              <text x="0" y="15" text-anchor="middle">N</text>
            </g>

            <!-- Every location: offices, showrooms and group stores -->
            <?php foreach ($mapPins as $index => $pin):
                $number = $index + 1;
                $offsetX = round($pin['tx'] - $pin['x'], 2);
                $offsetY = round($pin['ty'] - $pin['y'], 2);
                $label = $pin['city'] . ' — ' . $pin['type'];
                if ($pin['is_open']) {
                    $label .= ' (open now)';
                }
            ?>
              <g
                class="map-pin map-pin--<?= e($pin['category']) ?><?= $pin['is_open'] ? ' is-open' : '' ?>"
                data-target-location="<?= e($pin['id']) ?>"
                data-anchor="<?= e($pin['anchor']) ?>"
                data-anchor-kind="<?= e($pin['anchor_kind']) ?>"
                data-category="<?= e($pin['category']) ?>"
                data-province="<?= e($pin['province']) ?>"
                data-open="<?= $pin['is_open'] ? '1' : '0' ?>"
                data-search="<?= e($pin['search']) ?>"
                data-number="<?= $number ?>"
                transform="translate(<?= $pin['x'] ?> <?= $pin['y'] ?>)"
                tabindex="0"
                role="button"
                aria-label="<?= e($number . '. ' . $label) ?>"
              >
                <?php if ($pin['spread']): ?>
                  <line class="map-pin__leader" x1="<?= $offsetX ?>" y1="<?= $offsetY ?>" x2="0" y2="0"/>
                  <circle class="map-pin__origin" cx="<?= $offsetX ?>" cy="<?= $offsetY ?>" r="1.4"/>
                <?php endif; ?>
                <circle class="map-pin__hit" r="13"/>
                <circle class="map-pin__halo" r="<?= $pin['category'] === 'store' ? 9 : 11 ?>"/>
                <?php if ($pin['category'] === 'office'): ?>
                  <rect class="map-pin__core" x="-4.8" y="-4.8" width="9.6" height="9.6" rx="1.6"/>
                <?php else: ?>
                  <circle class="map-pin__core" r="<?= $pin['category'] === 'store' ? 3.9 : 4.8 ?>"/>
                <?php endif; ?>
                <text class="map-pin__num" x="<?= $pin['category'] === 'store' ? 8.4 : 9.6 ?>" y="3"><?= $number ?></text>
              </g>
            <?php endforeach; ?>
          </svg>
        </div>
        <p class="srilanka-map-panel__count">
          <strong><?= count($mapPins) ?></strong> locations mapped ·
          <strong><?= $mapCounts['office'] ?></strong> offices ·
          <strong><?= $mapCounts['showroom'] ?></strong> showrooms ·
          <strong><?= $mapCounts['store'] ?></strong> group stores
        </p>
        <p class="srilanka-map-panel__credit">
          District boundaries: <a href="https://mapsvg.com/maps/sri-lanka" rel="noopener nofollow">MapSVG</a> (CC BY 4.0)
        </p>
      </div>

      <!-- Zoomed detail for the dense western corridor -->
      <div class="map-detail" aria-label="Greater Colombo corridor detail map">
        <div class="srilanka-map-panel__head">
          <strong>Greater Colombo Corridor</strong>
          <span>Detail view</span>
        </div>
        <div class="map-detail__canvas">
          <svg viewBox="0 0 420 380" class="map-detail__svg" role="img" aria-labelledby="sl-detail-title">
            <title id="sl-detail-title">Detail map of the Softrings locations along the Colombo, Kaduwela, Homagama, Moratuwa and Avissawella corridor, numbered to match the national map.</title>
            <defs>
              <pattern id="detailGrid" width="26" height="26" patternUnits="userSpaceOnUse">
                <path d="M26 0H0V26" fill="none" stroke="rgba(0,85,171,0.09)" stroke-width="1"/>
              </pattern>
            </defs>
            <rect width="420" height="380" fill="url(#detailGrid)"/>

            <!-- Road corridors, drawn as a guide only -->
            <polyline class="map-detail__road" points="<?= e(implode(' ', $corridorPoints)) ?>"/>
            <text class="map-detail__road-label" x="86" y="286" transform="rotate(-8 86 286)">High Level Road</text>
            <text class="map-detail__road-label" x="196" y="272" transform="rotate(-40 196 272)">Avissawella Road</text>

            <?php foreach ($detailPins as $dp):
                $dOffsetX = round($dp['dx0'] - $dp['dx'], 2);
                $dOffsetY = round($dp['dy0'] - $dp['dy'], 2);
            ?>
              <g
                class="map-pin map-pin--detail map-pin--<?= e($dp['category']) ?><?= $dp['is_open'] ? ' is-open' : '' ?>"
                data-target-location="<?= e($dp['id']) ?>"
                data-anchor="<?= e($dp['anchor']) ?>"
                data-anchor-kind="<?= e($dp['anchor_kind']) ?>"
                data-category="<?= e($dp['category']) ?>"
                data-province="<?= e($dp['province']) ?>"
                data-open="<?= $dp['is_open'] ? '1' : '0' ?>"
                data-search="<?= e($dp['search']) ?>"
                data-number="<?= $dp['number'] ?>"
                transform="translate(<?= $dp['dx'] ?> <?= $dp['dy'] ?>)"
                tabindex="0"
                role="button"
                aria-label="<?= e($dp['number'] . '. ' . $dp['city'] . ' — ' . $dp['type']) ?>"
              >
                <?php if (hypot($dOffsetX, $dOffsetY) > 1 && hypot($dOffsetX, $dOffsetY) < 90): ?>
                  <line class="map-pin__leader" x1="<?= $dOffsetX ?>" y1="<?= $dOffsetY ?>" x2="0" y2="0"/>
                  <circle class="map-pin__origin" cx="<?= $dOffsetX ?>" cy="<?= $dOffsetY ?>" r="1.6"/>
                <?php endif; ?>
                <circle class="map-pin__hit" r="15"/>
                <circle class="map-pin__halo" r="13"/>
                <?php if ($dp['category'] === 'office'): ?>
                  <rect class="map-pin__core" x="-5.6" y="-5.6" width="11.2" height="11.2" rx="1.8"/>
                <?php else: ?>
                  <circle class="map-pin__core" r="<?= $dp['category'] === 'store' ? 4.6 : 5.6 ?>"/>
                <?php endif; ?>
                <text class="map-pin__num" x="10.4" y="3.4"><?= $dp['number'] ?></text>
              </g>
            <?php endforeach; ?>
          </svg>
        </div>
      </div>

      <!-- Map key: legend + numbered index of all locations -->
      <aside class="map-key" aria-label="Map key and location index">
        <div class="map-key__legend">
          <span><i class="map-key__marker map-key__marker--office"></i> Corporate Office</span>
          <span><i class="map-key__marker map-key__marker--showroom"></i> Showroom</span>
          <span><i class="map-key__marker map-key__marker--store"></i> Group Store</span>
          <span><i class="map-key__marker map-key__marker--open"></i> Open Now (SLST)</span>
        </div>
        <ol class="map-key__list">
          <?php foreach ($mapPins as $index => $pin): ?>
            <li class="map-key__item" data-category="<?= e($pin['category']) ?>">
              <button
                type="button"
                class="map-key__btn"
                data-map-target="<?= e($pin['id']) ?>"
                data-category="<?= e($pin['category']) ?>"
                data-province="<?= e($pin['province']) ?>"
                data-open="<?= $pin['is_open'] ? '1' : '0' ?>"
                data-search="<?= e($pin['search']) ?>"
              >
                <span class="map-key__num"><?= $index + 1 ?></span>
                <span class="map-key__text">
                  <span class="map-key__city"><?= e($pin['label']) ?></span>
                  <span class="map-key__type"><?= e($keyLabels[$pin['category']]) ?></span>
                </span>
              </button>
            </li>
          <?php endforeach; ?>
        </ol>
      </aside>

      <!-- One floating preview for both maps; positioned next to the marker -->
      <div class="map-tooltip" id="map-tooltip" role="status" hidden>
        <span class="map-tooltip__index"></span>
        <strong class="map-tooltip__name"></strong>
        <span class="map-tooltip__type"></span>
        <span class="map-tooltip__meta"></span>
        <span class="map-tooltip__hint">Click to open the full record</span>
      </div>
    </div>

    <div class="branch-explorer branch-explorer--cards">
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
            <tr
              id="<?= e($tp['id']) ?>"
              data-extended-location
              data-province="<?= e($tp['province']) ?>"
              data-search="<?= e(strtolower($tp['city'] . ' ' . $tp['address'] . ' ' . $tp['province'])) ?>"
            >
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
