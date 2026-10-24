<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Island-Wide Branch & Corporate Office Network';
$pageDescription = 'Locate Softrings International (Pvt) Ltd corporate offices and Authorized SLT-Mobitel showrooms in Maharagama, Gampola, Homagama, Kandy, Avissawella, Moratuwa, and Nugegoda, Sri Lanka.';
$activeNav = 'branches';

$branches = get_branches();
$extended = get_extended_touchpoints();

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
      <!-- Interactive Sri Lanka SVG Network Map -->
      <aside class="srilanka-map-panel" aria-label="Sri Lanka Branch Network Map">
        <div class="srilanka-map-panel__head">
          <strong>Sri Lanka Operational Map</strong>
          <span>Click a node to inspect hub</span>
        </div>
        <div class="srilanka-svg-wrap">
          <svg viewBox="0 0 360 520" class="srilanka-svg" role="img" aria-label="Map of Sri Lanka highlighting Softrings International branches in Western and Central Provinces">
            <defs>
              <linearGradient id="slLandGrad" x1="0.1" y1="0" x2="0.9" y2="1">
                <stop offset="0%" stop-color="#E8F7E4"/>
                <stop offset="48%" stop-color="#E4F1FC"/>
                <stop offset="100%" stop-color="#CFE4F7"/>
              </linearGradient>
              <linearGradient id="slCoastGrad" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#3AC22A"/>
                <stop offset="100%" stop-color="#0055AB"/>
              </linearGradient>
              <filter id="slLandShadow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="6" stdDeviation="7" flood-color="#00306F" flood-opacity="0.18"/>
              </filter>
            </defs>
            <!-- Stylized Accurate Sri Lanka Island Silhouette -->
            <path
              d="M150,28 C164,34 176,52 184,72 C195,98 214,126 232,162 C252,202 274,248 282,296 C289,338 284,386 262,424 C240,462 202,486 164,488 C132,489 106,470 96,440 C86,410 92,374 90,338 C88,298 78,258 84,218 C90,178 106,144 114,108 C120,80 124,48 136,32 C140,27 145,26 150,28 Z"
              fill="url(#slLandGrad)"
              stroke="url(#slCoastGrad)"
              stroke-width="2.4"
              stroke-opacity="0.55"
              filter="url(#slLandShadow)"
            />
            <!-- Western & Central Corridor Connection Line -->
            <polyline
              points="102,380 112,364 105,355 126,366 146,344 182,306 191,286"
              fill="none"
              stroke="#0055AB"
              stroke-width="2"
              stroke-dasharray="4 4"
              opacity="0.55"
            />
            <!-- Regional Labels -->
            <text x="198" y="274" class="map-region-label">Kandy</text>
            <text x="194" y="312" class="map-region-label">Gampola</text>
            <text x="154" y="342" class="map-region-label">Avissawella</text>
            <text x="40" y="362" class="map-region-label">Colombo / Maharagama</text>
            <text x="38" y="394" class="map-region-label">Moratuwa</text>

            <?php foreach ($branches as $b):
                $st = get_branch_live_status($b);
            ?>
              <g
                class="map-pin <?= $st['is_open'] ? 'is-open' : '' ?>"
                data-target-branch="<?= e($b['id']) ?>"
                tabindex="0"
                role="button"
                aria-label="<?= e($b['name']) ?>"
              >
                <circle cx="<?= (int) $b['map_x'] ?>" cy="<?= (int) $b['map_y'] ?>" r="11" class="map-pin__halo"/>
                <circle cx="<?= (int) $b['map_x'] ?>" cy="<?= (int) $b['map_y'] ?>" r="5.5" class="map-pin__core"/>
              </g>
            <?php endforeach; ?>
          </svg>
        </div>
        <div class="map-legend">
          <span><i class="legend-dot legend-dot--blue"></i> Corporate / Regional Center</span>
          <span><i class="legend-dot legend-dot--green"></i> Open Now (SLST)</span>
        </div>
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
