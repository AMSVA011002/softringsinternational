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
$leadership = get_leadership();

$yearsActive = (int) date('Y') - COMPANY_FOUNDED_GROUP;

/** Inline stroke icon set (24 × 24) shared by the section components. */
$icons = [
    'signal' => '<path d="M4 20v-4M9 20V12M14 20V7M19 20V3"/>',
    'handset' => '<rect x="6.5" y="2.5" width="11" height="19" rx="2"/><path d="M10.5 18.5h3"/>',
    'terminal' => '<rect x="2.5" y="4.5" width="19" height="13" rx="2"/><path d="M2.5 9.5h19M6 14h4"/><path d="M7 21h10"/>',
    'tools' => '<path d="M14.7 6.3a4 4 0 0 0 5 5l-9.4 9.4a2 2 0 0 1-2.8-2.8z"/><path d="M17.5 3.5l3 3"/>',
    'truck' => '<path d="M2.5 6h10.5v10H2.5z"/><path d="M13 9h4l4 3.6V16h-8"/><circle cx="6.5" cy="18.5" r="1.8"/><circle cx="17" cy="18.5" r="1.8"/>',
    'headset' => '<path d="M4 13v-1a8 8 0 0 1 16 0v1"/><path d="M4 13h2.5v5.5H5A1 1 0 0 1 4 17.5z"/><path d="M20 13h-2.5v5.5H19a1 1 0 0 0 1-1.5z"/><path d="M20 18.5v.5a3 3 0 0 1-3 3h-3"/>',
    'shield' => '<path d="M12 3l7 3v6c0 4.2-3 7.4-7 9-4-1.6-7-4.8-7-9V6z"/><path d="M9 12l2.2 2.2L15.5 10"/>',
    'pin' => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/>',
    'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/>',
    'store' => '<path d="M4 9h16v11H4z"/><path d="M3 9l2-5h14l2 5"/><path d="M9 20v-6h6v6"/>',
    'cash' => '<rect x="2.5" y="6" width="19" height="12" rx="1.5"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>',
    'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.8a3.5 3.5 0 0 1 0 6.4"/><path d="M17.5 14.2a6.5 6.5 0 0 1 4 5.8"/>',
    'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
    'mail' => '<rect x="2.5" y="5" width="19" height="14" rx="2"/><path d="M3 6l9 7 9-7"/>',
    'phone' => '<path d="M6.5 3.5h3l1.5 4-2 1.5a12 12 0 0 0 5.5 5.5l1.5-2 4 1.5v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2z"/>',
    'calendar' => '<rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
    'left' => '<path d="M15 5l-7 7 7 7"/>',
    'right' => '<path d="M9 5l7 7-7 7"/>',
];
$icon = static function (string $name, string $class = '') use ($icons): string {
    return '<svg class="' . $class . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" '
        . 'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . ($icons[$name] ?? '') . '</svg>';
};

/** Corporate gallery — uses only imagery already published in the project. */
$gallery = [
    [
        'src' => 'assets/images/hero-corporate.jpg',
        'alt' => 'Softrings International flagship SLT-Mobitel showroom counter in Colombo',
        'cat' => 'showrooms',
        'cat_label' => 'Showrooms',
        'title' => 'Colombo flagship SLT-Mobitel showroom',
        'size' => 'wide',
        'full' => 'assets/images/hero-corporate.jpg',
    ],
    [
        'src' => 'assets/images/distribution-network.jpg',
        'alt' => 'Multi-brand smartphone inventory prepared for island-wide distribution',
        'cat' => 'devices',
        'cat_label' => 'Devices',
        'title' => 'Multi-brand handset distribution',
        'size' => 'tall',
        'full' => 'assets/images/distribution-network.jpg',
    ],
    [
        'src' => 'assets/images/mpos-terminal.jpg',
        'alt' => 'Softrings mPOS wireless payment terminal printing a receipt on a retail counter',
        'cat' => 'mpos',
        'cat_label' => 'Softrings mPOS',
        'title' => 'mPOS terminal at a retail counter',
        'size' => '',
        'full' => 'assets/images/mpos-terminal.jpg',
    ],
    [
        'src' => 'assets/images/accessories-wall.jpg',
        'alt' => 'Wall display of original chargers, cases and screen protectors',
        'cat' => 'devices',
        'cat_label' => 'Devices',
        'title' => 'Genuine accessories wall',
        'size' => '',
        'full' => 'assets/images/accessories-wall.jpg',
    ],
    [
        'src' => 'assets/images/central-hill-country-hub.jpg',
        'alt' => 'Central Province telecom corridor spanning Kandy and Gampola',
        'cat' => 'network',
        'cat_label' => 'Network',
        'title' => 'Western &amp; Central operational corridors',
        'size' => 'wide',
        'full' => 'assets/images/central-hill-country-hub.jpg',
    ],
    [
        'src' => 'assets/images/logistics-dispatch.jpg',
        'alt' => 'Softrings delivery van and cartons at a warehouse dispatch bay',
        'cat' => 'logistics',
        'cat_label' => 'Logistics',
        'title' => 'In-house dispatch &amp; delivery fleet',
        'size' => '',
        'full' => 'assets/images/logistics-dispatch.jpg',
    ],
];

$posts = [
    [
        'day' => '01',
        'month' => 'Jul',
        'year' => '2010',
        'cat' => 'Distribution',
        'title' => 'Softrings Phone Company appointed National Distributor for Alcatel OneTouch',
        'excerpt' => 'Commencing 01 July 2010 at Stanley Thilakaratne Mawatha, Nugegoda, the company took on island-wide Alcatel OneTouch handset and HSDPA modem distribution.',
        'img' => 'assets/images/distribution-network.jpg',
        'alt' => 'Alcatel OneTouch handset distribution cartons',
        'links' => ['Group Structure' => 'about.php#group-structure', 'Divisions' => 'services.php'],
        'author' => 'Corporate Communications',
    ],
    [
        'day' => '11',
        'month' => 'Sep',
        'year' => '2009',
        'cat' => 'Corporate',
        'title' => 'Softrings International incorporated as the Group flagship enterprise',
        'excerpt' => 'Commenced on 11 September 2009 to consolidate multi-brand handset retail, original accessories, corporate procurement and SLT-Mobitel dealer operations.',
        'img' => 'assets/images/hero-corporate.jpg',
        'alt' => 'Softrings International showroom interior',
        'links' => ['Leadership' => 'about.php#leadership', 'Our Story' => 'about.php'],
        'author' => 'Corporate Secretary',
    ],
    [
        'day' => '02',
        'month' => 'Jul',
        'year' => '2003',
        'cat' => 'Network',
        'title' => 'Softrings Technologies scales territory-wide SLT-Mobitel distribution',
        'excerpt' => 'From 02 July 2003 the Group extended authorised wholesale distribution to Maharagama, Homagama, Avissawella, Moratuwa, Kandy and Gampola.',
        'img' => 'assets/images/central-hill-country-hub.jpg',
        'alt' => 'Central Province telecom corridor',
        'links' => ['Branch Network' => 'branches.php', 'Dealer Onboarding' => 'contact.php?type=Sub-Dealer+%2F+mPOS+Onboarding'],
        'author' => 'Distribution Desk',
    ],
];

$plans = [
    [
        'tier' => 'Tier 01',
        'name' => 'Retail Walk-in',
        'price' => 'Open',
        'price_note' => 'Every showroom',
        'featured' => false,
        'features' => [
            'Authorised SLT-Mobitel connections & reloads',
            'Genuine TRCSL-approved handsets',
            'Bill payment, mCash & warranty desks',
            'Accessories and device setup support',
        ],
        'cta' => 'Find a Showroom',
        'href' => 'branches.php',
    ],
    [
        'tier' => 'Tier 02',
        'name' => 'Sub-Dealer',
        'price' => 'Apply',
        'price_note' => 'On assessment',
        'featured' => false,
        'features' => [
            'Wholesale reload & scratch-card supply',
            'Handset and accessory dealer pricing',
            'Softrings mPOS terminal deployment',
            'Dedicated field executive support',
        ],
        'cta' => 'Request Dealer Account',
        'href' => 'contact.php?type=Sub-Dealer+%2F+mPOS+Onboarding',
    ],
    [
        'tier' => 'Tier 03',
        'name' => 'Corporate',
        'price' => 'Review',
        'price_note' => 'On application',
        'featured' => true,
        'features' => [
            'Bulk handset & tablet procurement',
            'Enterprise SLT-Mobitel packages',
            'Customised corporate invoicing',
            'Delivery to registered offices',
        ],
        'cta' => 'Submit Corporate Inquiry',
        'href' => 'contact.php?type=Enterprise+%2F+Bulk+Handset+Procurement',
    ],
    [
        'tier' => 'Tier 04',
        'name' => 'Wholesale',
        'price' => 'By Review',
        'price_note' => 'National partners',
        'featured' => false,
        'features' => [
            'National brand distribution mandates',
            'Consolidated logistics & fleet dispatch',
            'Tailored mPOS float arrangements',
            'Quarterly partnership reviews',
        ],
        'cta' => 'Talk to Wholesale Desk',
        'href' => 'contact.php?type=Multi-Brand+Smartphones+%26+Accessories+Distribution',
    ],
];

$faqs = [
    [
        'q' => 'How does a retail shop become an authorised Softrings sub-dealer?',
        'a' => 'Submit the sub-dealer / mPOS onboarding form with your shop address, contact details and current product mix. Our field executives assess territory coverage and credit terms, then schedule a visit from the nearest regional office — Maharagama, Homagama, Nugegoda, Kandy or Gampola.',
    ],
    [
        'q' => 'Are the handsets you distribute covered by an official warranty?',
        'a' => 'Yes. Softrings International distributes TRCSL-approved, network-compatible handsets and genuine accessories backed by manufacturer warranty. Claims are coordinated through our in-house technical service lab and regional service points.',
    ],
    [
        'q' => 'What can a Softrings mPOS terminal do for my shop?',
        'a' => 'One terminal handles mobile reloads, postpaid and utility bill settlement, e-vouchers and daily commission tracking with thermal receipt printing. Terminals are installed by our merchant team, who also train staff and manage float top-ups.',
    ],
    [
        'q' => 'Can you supply handsets for corporate or institutional bulk orders?',
        'a' => 'Yes. Our corporate desk handles bulk handset and tablet procurement with customised invoicing for Sri Lankan SMEs, enterprises and state-sector buyers, delivered through our own Colombo and Kandy logistics fleet.',
    ],
    [
        'q' => 'Where are your offices and when are they open?',
        'a' => 'Corporate offices operate in Maharagama (No. 65/10, Piliyandala Road) and Gampola (No. 102, Nuwara Eliya Road), with showrooms in Homagama, Maharagama, Moratuwa, Avissawella, Nugegoda and Kandy. Standard hours are Monday to Friday 8:30 AM – 6:00 PM and Saturday 9:00 AM – 2:00 PM, with extended daily hours at the Homagama flagship.',
    ],
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ======================================================================
     Hero slider
     ====================================================================== -->
<section class="atr-hero" id="top" aria-label="Softrings International corporate highlights">
  <div class="atr-hero__viewport" data-hero>
    <article class="atr-hero__slide is-active" data-hero-slide aria-hidden="false">
      <img class="atr-hero__media" src="assets/images/hero-corporate.jpg" alt="Softrings International flagship SLT-Mobitel showroom and customer service centre in Colombo" width="1280" height="720" fetchpriority="high">
      <div class="atr-hero__shade" aria-hidden="true"></div>
      <div class="container atr-hero__content">
        <p class="atr-hero__eyebrow">Authorised SLT-Mobitel Dealer · Softrings Group Since 2000</p>
        <h1 class="atr-hero__title">Connecting Sri Lanka Through <strong>Trusted Telecom</strong></h1>
        <p class="atr-hero__text">
          Authorised <strong>SLT-Mobitel</strong> showrooms, island-wide multi-brand handset distribution and <strong>Softrings mPOS</strong> merchant terminals — delivered with two decades of group integrity.
        </p>
        <div class="atr-hero__actions">
          <a href="about.php" class="btn btn--primary btn--lg">Explore Corporate Profile</a>
          <a href="branches.php" class="btn btn--outline-light btn--lg">Locate Our 8 Centers</a>
        </div>
      </div>
    </article>

    <article class="atr-hero__slide" data-hero-slide aria-hidden="true">
      <img class="atr-hero__media" src="assets/images/distribution-network.jpg" alt="Multi-brand smartphones and accessories prepared for island-wide distribution" width="1280" height="720" loading="lazy">
      <div class="atr-hero__shade" aria-hidden="true"></div>
      <div class="container atr-hero__content">
        <p class="atr-hero__eyebrow">Island-Wide Device Distribution</p>
        <h2 class="atr-hero__title">Genuine Handsets. <strong>Authorised Warranty.</strong></h2>
        <p class="atr-hero__text">
          Samsung, Nokia, Huawei, Xiaomi and Alcatel supply for retail and sub-dealer networks — backed by TRCSL compliance and our in-house technical service lab.
        </p>
        <div class="atr-hero__actions">
          <a href="services.php" class="btn btn--primary btn--lg">View Business Divisions</a>
          <a href="contact.php?type=Sub-Dealer+%2F+mPOS+Onboarding" class="btn btn--outline-light btn--lg">Apply for Dealer Account</a>
        </div>
      </div>
    </article>

    <article class="atr-hero__slide" data-hero-slide aria-hidden="true">
      <img class="atr-hero__media" src="assets/images/mpos-terminal.jpg" alt="Softrings mPOS wireless payment terminal on a retail counter" width="1280" height="720" loading="lazy">
      <div class="atr-hero__shade" aria-hidden="true"></div>
      <div class="container atr-hero__content">
        <p class="atr-hero__eyebrow">Softrings mPOS · Digital Merchant Terminals</p>
        <h2 class="atr-hero__title">One Terminal for <strong>Reloads, Bills &amp; Payments</strong></h2>
        <p class="atr-hero__text">
          Compact wireless terminals for groceries, pharmacies and communication shops — instant settlement, thermal receipts and daily commission tracking.
        </p>
        <div class="atr-hero__actions">
          <a href="services.php#mpos-calculator" class="btn btn--primary btn--lg">Estimate Partner Earnings</a>
          <a href="contact.php?type=Softrings+mPOS+%26+Digital+Merchant+Terminals" class="btn btn--outline-light btn--lg">Talk to Merchant Desk</a>
        </div>
      </div>
    </article>

    <div class="container atr-hero__controls">
      <div class="atr-hero__dots" role="group" aria-label="Hero slide selection">
        <button type="button" class="atr-hero__dot is-active" data-hero-dot="0" aria-label="Show slide 1: Corporate overview" aria-pressed="true"></button>
        <button type="button" class="atr-hero__dot" data-hero-dot="1" aria-label="Show slide 2: Device distribution" aria-pressed="false"></button>
        <button type="button" class="atr-hero__dot" data-hero-dot="2" aria-label="Show slide 3: Softrings mPOS" aria-pressed="false"></button>
      </div>
      <span class="atr-hero__counter" aria-hidden="true"><span data-hero-index>01</span> / 03</span>
      <div class="atr-hero__arrows">
        <button type="button" class="atr-hero__arrow" data-hero-prev aria-label="Previous slide">
          <?= $icon('left') ?>
        </button>
        <button type="button" class="atr-hero__arrow" data-hero-next aria-label="Next slide">
          <?= $icon('right') ?>
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Overlapping corporate ledger -->
<div class="atr-statbar">
  <div class="container atr-statbar__inner">
    <div class="atr-statbar__item">
      <span class="atr-statbar__num"><?= $yearsActive ?>+ Yrs</span>
      <span class="atr-statbar__label">Years of Group Legacy</span>
    </div>
    <div class="atr-statbar__item">
      <span class="atr-statbar__num">08 Centers</span>
      <span class="atr-statbar__label">Showrooms &amp; Hubs</span>
    </div>
    <div class="atr-statbar__item">
      <span class="atr-statbar__num">04 Entities</span>
      <span class="atr-statbar__label">Group Companies</span>
    </div>
    <div class="atr-statbar__item">
      <span class="atr-statbar__num">SLT-Mobitel</span>
      <span class="atr-statbar__label">Authorised Dealer</span>
    </div>
  </div>
</div>

<!-- ======================================================================
     About
     ====================================================================== -->
<section class="atr-section" id="about">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Who We Are</span>
      <h2 class="atr-heading">A Sri Lankan Telecom House Built on <strong>Lasting Trust</strong></h2>
      <p class="atr-sub">Two decades of authorised dealership, genuine devices and dependable island-wide service.</p>
    </header>

    <div class="atr-about">
      <figure class="atr-figure">
        <img src="assets/images/hero-corporate.jpg" alt="Softrings International showroom team serving a customer at the SLT-Mobitel counter" width="1280" height="1024" loading="lazy">
        <figcaption class="atr-figure__tag">
          <strong><?= $yearsActive ?>+ Years</strong>
          Softrings Group Heritage
        </figcaption>
      </figure>

      <div class="atr-copy">
        <h2>Connecting Colombo to the <strong>Central Hill Country</strong></h2>
        <p class="atr-sub">&ldquo;It is our ultimate target to maintain our good name in the market.&rdquo;</p>
        <p>
          Originating in <strong>2000</strong> as an Authorised Dealer for <strong>Sri Lanka Telecom Mobitel</strong>, the Softrings Group grew from a single dealership into four operating companies. <strong>Softrings International (Pvt) Ltd</strong> was incorporated on <strong>11 September 2009</strong> to anchor multi-brand handset retail, original accessories supply, corporate device procurement and showroom operations.
        </p>
        <ul class="atr-bullets">
          <li>Authorised SLT-Mobitel dealer operations across six core territories</li>
          <li>National distribution heritage as appointed Alcatel OneTouch distributor</li>
          <li>In-house technical lab, warranty coordination and customer care desks</li>
          <li>Dual corporate presence in Maharagama and Gampola</li>
        </ul>
        <div class="hero-actions" style="margin-bottom:0;">
          <a href="about.php" class="btn btn--secondary">Read the Group Story</a>
          <a href="about.php#leadership" class="btn btn--primary">Meet the Board</a>
        </div>
      </div>
    </div>

    <div class="atr-feature-grid">
      <div class="atr-feature">
        <span class="atr-feature__icon"><?= $icon('shield') ?></span>
        <h3>Authentic Warranty</h3>
        <p>TRCSL-compliant devices and original accessories with manufacturer-backed claims.</p>
      </div>
      <div class="atr-feature">
        <span class="atr-feature__icon"><?= $icon('globe') ?></span>
        <h3>Territory Depth</h3>
        <p>Western and Central Province corridors linked by our own dispatch fleet.</p>
      </div>
      <div class="atr-feature">
        <span class="atr-feature__icon"><?= $icon('users') ?></span>
        <h3>The Human Factor</h3>
        <p>Trained customer care, certified technicians and dedicated field executives.</p>
      </div>
      <div class="atr-feature">
        <span class="atr-feature__icon"><?= $icon('store') ?></span>
        <h3>Company-Backed Service</h3>
        <p>Every showroom is operated directly by the company, not a franchise counter.</p>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     Services
     ====================================================================== -->
<section class="atr-section atr-section--tint" id="services">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Our Divisions</span>
      <h2 class="atr-heading">Integrated Telecom, Device &amp; <strong>FinTech Infrastructure</strong></h2>
      <p class="atr-sub">Four operating divisions serving subscribers, retailers, merchants and enterprise buyers.</p>
    </header>

    <div class="atr-grid-4">
      <?php
      $shortTitles = [
          'mobitel-dealer' => 'SLT-Mobitel Dealer Network',
          'mobile-distribution' => 'Device Distribution',
          'mpos-solutions' => 'Softrings mPOS',
          'technical-care' => 'Technical Service Lab',
      ];
      foreach ($services as $srv): ?>
        <article class="atr-card">
          <span class="atr-card__icon">
            <?= $icon(match ($srv['slug']) {
                'mobitel-dealer' => 'signal',
                'mobile-distribution' => 'handset',
                'mpos-solutions' => 'terminal',
                default => 'tools',
            }) ?>
          </span>
          <span class="atr-card__index"><?= e($srv['code']) ?></span>
          <h3><?= e($shortTitles[$srv['slug']] ?? $srv['title']) ?></h3>
          <p><?= e($srv['subtitle']) ?></p>
          <div class="atr-card__foot">
            <span><?= e($srv['metrics']) ?></span>
            <a href="services.php#<?= e($srv['slug']) ?>">Details &rarr;</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <figure class="atr-quote">
      <span class="atr-quote__mark" aria-hidden="true">&ldquo;</span>
      <blockquote><?= e($charter['quote']) ?></blockquote>
      <cite>Official Corporate Charter · Softrings International (Pvt) Ltd</cite>
    </figure>

    <div class="atr-stats">
      <div class="atr-stat">
        <span class="atr-stat__num"><?= $yearsActive ?></span>
        <span class="atr-stat__label">Years of Group Heritage</span>
      </div>
      <div class="atr-stat">
        <span class="atr-stat__num"><?= count($branches) ?></span>
        <span class="atr-stat__label">Showrooms &amp; Corporate Hubs</span>
      </div>
      <div class="atr-stat">
        <span class="atr-stat__num"><?= count($entities) ?></span>
        <span class="atr-stat__label">Operating Group Companies</span>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     Portfolio gallery
     ====================================================================== -->
<section class="atr-section" id="work">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Our Work</span>
      <h2 class="atr-heading">Inside the <strong>Softrings Network</strong></h2>
      <p class="atr-sub">Showrooms, distribution floors, merchant terminals and delivery corridors.</p>
    </header>

    <div class="atr-filters" role="group" aria-label="Filter gallery by category">
      <button type="button" class="atr-filter is-active" data-gallery-filter="all" aria-pressed="true">All Items</button>
      <button type="button" class="atr-filter" data-gallery-filter="showrooms" aria-pressed="false">Showrooms</button>
      <button type="button" class="atr-filter" data-gallery-filter="devices" aria-pressed="false">Devices</button>
      <button type="button" class="atr-filter" data-gallery-filter="mpos" aria-pressed="false">mPOS</button>
      <button type="button" class="atr-filter" data-gallery-filter="network" aria-pressed="false">Network</button>
      <button type="button" class="atr-filter" data-gallery-filter="logistics" aria-pressed="false">Logistics</button>
    </div>

    <div class="atr-gallery" data-gallery>
      <?php foreach ($gallery as $i => $item): ?>
        <button
          type="button"
          class="atr-tile <?= $item['size'] === 'wide' ? 'atr-tile--wide' : ($item['size'] === 'tall' ? 'atr-tile--tall' : '') ?>"
          data-gallery-item
          data-cat="<?= e($item['cat']) ?>"
          data-full="<?= e($item['full']) ?>"
          data-caption="<?= e($item['title']) ?>"
          data-cat-label="<?= e($item['cat_label']) ?>"
          data-index="<?= $i ?>"
          aria-label="View larger image: <?= e(strip_tags($item['title'])) ?>"
        >
          <img src="<?= e($item['src']) ?>" alt="<?= e($item['alt']) ?>" width="900" height="700" loading="lazy">
          <span class="atr-tile__overlay">
            <span class="atr-tile__cat"><?= e($item['cat_label']) ?></span>
            <span class="atr-tile__title"><?= $item['title'] ?></span>
          </span>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ======================================================================
     Team
     ====================================================================== -->
<section class="atr-section atr-section--tint" id="team">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Corporate Governance</span>
      <h2 class="atr-heading">Meet the <strong>Board of Directors</strong></h2>
      <p class="atr-sub">Strategic oversight for the Group&rsquo;s dealership, distribution and merchant operations.</p>
    </header>

    <div class="atr-team">
      <?php foreach ($leadership['board'] as $director):
          $initials = '';
          foreach (preg_split('/\s+/', str_replace(['Mr.', 'Mrs.', 'Ms.'], '', $director['name'])) as $part) {
              if ($part !== '' && ctype_alpha($part[0])) {
                  $initials .= strtoupper($part[0]);
              }
          }
          $initials = substr($initials, 0, 2);
      ?>
        <button
          type="button"
          class="atr-member"
          data-member
          data-name="<?= e($director['name']) ?>"
          data-role="<?= e($director['role']) ?>"
          data-email="<?= e($director['email']) ?>"
          data-init="<?= e($initials) ?>"
          data-photo="<?= e($director['photo']) ?>"
          aria-haspopup="dialog"
        >
          <span class="atr-member__portrait">
            <img
              class="atr-member__photo"
              src="<?= e($director['photo']) ?>"
              alt="Placeholder portrait for <?= e($director['name']) ?>, <?= e($director['role']) ?> — official photograph to follow"
              width="640"
              height="800"
              loading="lazy"
            >
            <span class="atr-member__tag">Photo pending</span>
            <span class="atr-member__zoom" aria-hidden="true">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5M11 8.5v5M8.5 11h5"/></svg>
            </span>
          </span>
          <span class="atr-member__body">
            <span class="atr-member__role"><?= e($director['role']) ?></span>
            <span class="atr-member__name"><?= e($director['name']) ?></span>
            <span class="atr-member__meta">Softrings Group of Companies</span>
          </span>
          <span class="atr-member__bio"><?= e($director['bio']) ?></span>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="atr-head" style="margin:44px auto 0;">
      <a href="about.php#leadership" class="btn btn--secondary">View Executive Management Directory</a>
    </div>
  </div>
</section>

<!-- ======================================================================
     Updates
     ====================================================================== -->
<section class="atr-section atr-section--navy" id="updates">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker atr-kicker--light">Group Milestones</span>
      <h2 class="atr-heading atr-heading--light">What&rsquo;s <strong>New</strong></h2>
      <p class="atr-sub">Key dates in the Softrings Group&rsquo;s telecommunications story.</p>
    </header>

    <div class="atr-posts">
      <?php foreach ($posts as $post): ?>
        <article class="atr-post">
          <div class="atr-post__media">
            <img src="<?= e($post['img']) ?>" alt="<?= e($post['alt']) ?>" width="900" height="500" loading="lazy">
            <div class="atr-post__date">
              <strong><?= e($post['day']) ?></strong>
              <span><?= e($post['month']) ?></span>
            </div>
          </div>
          <div class="atr-post__body">
            <span class="atr-post__cat"><?= e($post['cat']) ?> · <?= e($post['year']) ?></span>
            <h3><?= e($post['title']) ?></h3>
            <p><?= e($post['excerpt']) ?></p>
            <div class="atr-post__meta">
              <span><?= e($post['author']) ?></span>
              <?php foreach ($post['links'] as $label => $href): ?>
                <a href="<?= e($href) ?>"><?= e($label) ?></a>
              <?php endforeach; ?>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ======================================================================
     Partnership plans
     ====================================================================== -->
<section class="atr-section" id="plans">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Partnership Tiers</span>
      <h2 class="atr-heading">Choose How You <strong>Work With Us</strong></h2>
      <p class="atr-sub">From a walk-in showroom visit to a national distribution mandate.</p>
    </header>

    <div class="atr-plans">
      <?php foreach ($plans as $plan): ?>
        <article class="atr-plan <?= $plan['featured'] ? 'atr-plan--featured' : '' ?>">
          <span class="atr-plan__tier"><?= e($plan['tier']) ?></span>
          <h3 class="atr-plan__name"><?= e($plan['name']) ?></h3>
          <p class="atr-plan__price"><?= e($plan['price']) ?><span><?= e($plan['price_note']) ?></span></p>
          <span class="atr-plan__rule" aria-hidden="true"></span>
          <ul class="atr-plan__list">
            <?php foreach ($plan['features'] as $feature): ?>
              <li><?= e($feature) ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="<?= e($plan['href']) ?>" class="btn <?= $plan['featured'] ? 'btn--primary' : 'btn--secondary' ?>"><?= e($plan['cta']) ?></a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ======================================================================
     Capabilities
     ====================================================================== -->
<section class="atr-section atr-section--navy" id="capabilities">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker atr-kicker--light">Every Softrings Center</span>
      <h2 class="atr-heading atr-heading--light">One Counter, <strong>Complete Service</strong></h2>
      <p class="atr-sub">A single visit covers connections, devices, payments, warranty and delivery.</p>
    </header>

    <div class="atr-combines">
      <div class="atr-combine"><?= $icon('signal') ?><span>SIM &amp; Broadband</span></div>
      <div class="atr-combine"><?= $icon('handset') ?><span>Genuine Handsets</span></div>
      <div class="atr-combine"><?= $icon('terminal') ?><span>mPOS Terminals</span></div>
      <div class="atr-combine"><?= $icon('cash') ?><span>Bill Settlement</span></div>
      <div class="atr-combine"><?= $icon('tools') ?><span>Warranty Lab</span></div>
      <div class="atr-combine"><?= $icon('truck') ?><span>In-House Delivery</span></div>
    </div>
  </div>
</section>

<!-- ======================================================================
     FAQ
     ====================================================================== -->
<section class="atr-section" id="faq">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Questions &amp; Answers</span>
      <h2 class="atr-heading">Frequently Asked <strong>Questions</strong></h2>
      <p class="atr-sub">Dealership, warranty, merchant terminals and corporate procurement.</p>
    </header>

    <div class="atr-faq">
      <div class="atr-faq__list">
        <?php foreach ($faqs as $i => $faq): ?>
          <div class="atr-faq__item">
            <button type="button" class="atr-faq__q" id="faq-q-<?= $i ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="faq-a-<?= $i ?>" data-faq-toggle>
              <?= e($faq['q']) ?>
            </button>
            <div class="atr-faq__a" id="faq-a-<?= $i ?>" role="region" aria-labelledby="faq-q-<?= $i ?>" <?= $i === 0 ? '' : 'hidden' ?>>
              <?= e($faq['a']) ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <aside class="atr-faq__aside">
        <span class="atr-kicker">Still Need Help?</span>
        <h3>Speak to a Corporate Desk</h3>
        <p>Our Colombo and Gampola teams answer dealer, warranty and procurement questions directly.</p>
        <div class="atr-faq__aside-row"><span>Colombo</span><strong>+94 71 700 0090</strong></div>
        <div class="atr-faq__aside-row"><span>Gampola</span><strong>+94 71 404 1369</strong></div>
        <div class="atr-faq__aside-row"><span>Kandy</span><strong>+94 81 222 7417</strong></div>
        <div class="atr-faq__aside-row"><span>Email</span><strong><?= e(COMPANY_PRIMARY_EMAIL) ?></strong></div>
        <p style="margin-top:18px;margin-bottom:0;">
          <a href="contact.php" class="btn btn--primary btn--full">Send an Inquiry</a>
        </p>
      </aside>
    </div>
  </div>
</section>

<!-- ======================================================================
     Contact
     ====================================================================== -->
<section class="atr-section atr-section--tint" id="contact">
  <div class="container">
    <header class="atr-head">
      <span class="atr-kicker">Contact Us</span>
      <h2 class="atr-heading">Let&rsquo;s Build the <strong>Next Connection</strong></h2>
      <p class="atr-sub">Every submission is logged with an SFT reference code you can track online.</p>
    </header>

    <div class="atr-contact">
      <div class="atr-contact__info">
        <h2>Corporate &amp; Trade Desks</h2>
        <p class="atr-sub">Direct lines to the teams that handle dealerships, devices and merchant terminals.</p>

        <ul class="atr-contact__list">
          <li>
            <?= $icon('pin') ?>
            <span>
              <strong>Colombo Corporate Office</strong>
              No. 65/10, Piliyandala Road, Maharagama 10280
            </span>
          </li>
          <li>
            <?= $icon('pin') ?>
            <span>
              <strong>Central Province Corporate Office</strong>
              No. 102, Nuwara Eliya Road, Gampola 20500
            </span>
          </li>
          <li>
            <?= $icon('phone') ?>
            <span>
              <strong>Corporate Hotline</strong>
              <a href="tel:+94717000090">+94 71 700 0090</a> · <a href="tel:+94812227417">+94 81 222 7417</a>
            </span>
          </li>
          <li>
            <?= $icon('mail') ?>
            <span>
              <strong>Email</strong>
              <a href="mailto:<?= e(COMPANY_PRIMARY_EMAIL) ?>"><?= e(COMPANY_PRIMARY_EMAIL) ?></a>
            </span>
          </li>
          <li>
            <?= $icon('clock') ?>
            <span>
              <strong>Standard Hours</strong>
              Mon–Fri 8:30 AM – 6:00 PM · Sat 9:00 AM – 2:00 PM
            </span>
          </li>
        </ul>

        <p style="margin-top:24px;margin-bottom:0;">
          <a href="branches.php" class="btn btn--outline-light btn--sm">Open Branch Locator</a>
        </p>
      </div>

      <div class="atr-contact__form">
        <h3>Send a Corporate or Trade Message</h3>
        <p class="form-panel__sub">Complete the form and receive an instant reference number for follow-up.</p>

        <div id="contact-ajax-feedback" aria-live="polite"></div>

        <form method="post" action="contact.php" id="contact-form" class="corp-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="preferred_branch" value="Colombo Corporate Office — Maharagama">

          <div class="form-row-2">
            <div class="form-field">
              <label for="home_full_name">Your Full Name *</label>
              <input type="text" id="home_full_name" name="full_name" required placeholder="e.g. Nimal Jayawardena">
            </div>
            <div class="form-field">
              <label for="home_company">Organization / Shop</label>
              <input type="text" id="home_company" name="company_name" placeholder="Optional for B2B">
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="home_phone">Telephone / WhatsApp *</label>
              <input type="tel" id="home_phone" name="phone" required placeholder="+94 71 XXX XXXX">
            </div>
            <div class="form-field">
              <label for="home_email">Email Address *</label>
              <input type="email" id="home_email" name="email" required placeholder="you@company.lk">
            </div>
          </div>

          <div class="form-field">
            <label for="home_type">Nature of Inquiry</label>
            <select id="home_type" name="inquiry_type">
              <option>General Corporate Inquiry</option>
              <option>Sub-Dealer / mPOS Onboarding</option>
              <option>Enterprise / Bulk Handset Procurement</option>
              <option>Softrings mPOS &amp; Digital Merchant Terminals</option>
              <option>Technical Service Lab &amp; Customer Care</option>
            </select>
          </div>

          <div class="form-field">
            <label for="home_message">Requirement Details *</label>
            <textarea id="home_message" name="message" rows="4" required placeholder="Tell us about your shop, device requirement or partnership plan..."></textarea>
          </div>

          <button type="submit" class="btn btn--primary btn--full">Submit Official Inquiry</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Gallery lightbox -->
<div class="atr-lightbox" id="atr-lightbox" role="dialog" aria-modal="true" aria-label="Image viewer" hidden>
  <figure class="atr-lightbox__figure">
    <button type="button" class="atr-lightbox__close" data-lightbox-close aria-label="Close image viewer">&times;</button>
    <img class="atr-lightbox__img" src="" alt="" data-lightbox-img>
    <figcaption class="atr-lightbox__caption">
      <span data-lightbox-cat></span>
      <strong data-lightbox-caption></strong>
    </figcaption>
    <button type="button" class="atr-lightbox__nav atr-lightbox__nav--prev" data-lightbox-prev aria-label="Previous image">
      <?= $icon('left') ?>
    </button>
    <button type="button" class="atr-lightbox__nav atr-lightbox__nav--next" data-lightbox-next aria-label="Next image">
      <?= $icon('right') ?>
    </button>
  </figure>
</div>

<!-- Board member modal -->
<div class="atr-modal" id="atr-member-modal" role="dialog" aria-modal="true" aria-labelledby="atr-member-name" hidden>
  <div class="atr-modal__card">
    <button type="button" class="atr-modal__close" data-member-close aria-label="Close director profile">&times;</button>
    <img class="atr-modal__photo" src="" alt="" width="640" height="800" data-member-photo hidden>
    <span class="atr-modal__role" data-member-role></span>
    <h3 class="atr-modal__name" id="atr-member-name" data-member-name></h3>
    <p class="atr-modal__bio" data-member-bio></p>
    <div class="atr-modal__links">
      <a href="#" class="btn btn--primary btn--sm" data-member-mail>Email the Director</a>
      <a href="about.php#leadership" class="btn btn--secondary btn--sm">Full Management Directory</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
