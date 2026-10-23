<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Partner, Dealer & Corporate Contact Desk';
$pageDescription = 'Contact Softrings International (Pvt) Ltd in Maharagama, Gampola, Homagama, or Kandy for corporate mobile procurement, Authorized SLT-Mobitel services, or Softrings mPOS sub-dealer onboarding.';
$activeNav = 'contact';

$branches = get_branches();
$preselectedType = trim((string) ($_GET['type'] ?? 'General Corporate Inquiry'));
$preselectedBranch = trim((string) ($_GET['branch'] ?? 'Colombo Corporate Office — Maharagama'));

$inquiryTypes = [
    'General Corporate Inquiry',
    'Sub-Dealer / mPOS Onboarding',
    'Authorized SLT-Mobitel DealerNetwork',
    'Authorized SLT-Mobitel Dealer Network',
    'Multi-Brand Smartphones & Accessories Distribution',
    'Softrings mPOS & Digital Merchant Terminals',
    'Enterprise / Bulk Handset Procurement',
    'Technical Service Lab & Customer Care',
];
// Deduplicate clean list
$cleanTypes = [
    'General Corporate Inquiry',
    'Sub-Dealer / mPOS Onboarding',
    'Enterprise / Bulk Handset Procurement',
    'Authorized SLT-Mobitel Dealer Network',
    'Multi-Brand Smartphones & Accessories Distribution',
    'Softrings mPOS & Digital Merchant Terminals',
    'Technical Service Lab & Customer Care',
];

$formSuccess = null;
$formError = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf((string) ($_POST['csrf_token'] ?? ''))) {
        $formError = 'Security token expired. Please reload and submit again.';
    } else {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $companyName = trim((string) ($_POST['company_name'] ?? ''));
        $inquiryType = trim((string) ($_POST['inquiry_type'] ?? 'General Corporate Inquiry'));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $preferredBranch = trim((string) ($_POST['preferred_branch'] ?? 'Colombo Corporate Office — Maharagama'));
        $message = trim((string) ($_POST['message'] ?? ''));

        if (mb_strlen($fullName) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($message) < 10) {
            $formError = 'Please complete your name, valid email, contact number, and message details.';
        } else {
            $formSuccess = save_corporate_inquiry([
                'inquiry_type' => $inquiryType,
                'full_name' => $fullName,
                'company_name' => $companyName,
                'phone' => $phone,
                'email' => $email,
                'preferred_branch' => $preferredBranch,
                'message' => $message,
            ]);
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container page-hero__inner">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="index.php">Overview</a>
      <span aria-hidden="true">/</span>
      <span>Partner &amp; Contact</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">Direct Corporate &amp; Trade Desk</span>
        <h1>Connect With Softrings International</h1>
        <p class="page-hero__lead">
          Reach our Colombo Corporate Office in Maharagama, our Central Province Corporate Office in Gampola, or our regional SLT-Mobitel dealer teams for wholesale, enterprise, and subscriber support.
        </p>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Colombo Hotline</span>
          <strong>+94 71 700 0090 / +94 11 285 0998</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Central / Gampola &amp; Kandy</span>
          <strong>+94 71 404 1369 / +94 81 222 7417</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Homagama Flagship</span>
          <strong>+94 11 289 5061 / +94 70 873 8611</strong>
        </div>
        <div class="dossier-meta-row">
          <span>Corporate Email</span>
          <strong><?= e(COMPANY_PRIMARY_EMAIL) ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container contact-layout">
    <!-- Left Column: Direct Desks -->
    <div class="contact-layout__info">
      <span class="eyebrow">Direct Channels</span>
      <h2 class="section-title">Regional Corporate Desks</h2>
      <p class="prose-text">
        Every online submission generates an official <code>SFT-<?= date('Y') ?></code> reference code stored in our corporate desk ledger so you can check the status of your inquiry anytime.
      </p>

      <div class="direct-desk-cards">
        <div class="desk-card">
          <span class="desk-card__tag">Western Province HQ</span>
          <h3>Colombo Corporate Office — Maharagama</h3>
          <p>No. 65/10, Piliyandala Road, Maharagama 10280, Sri Lanka</p>
          <p class="desk-card__links">
            <a href="tel:+94717000090">+94 71 700 0090</a> ·
            <a href="tel:+94718728635">+94 71 872 8635</a>
          </p>
        </div>

        <div class="desk-card">
          <span class="desk-card__tag">Central Province HQ</span>
          <h3>Gampola &amp; Kandy Corporate Offices</h3>
          <p>No. 102, Nuwara Eliya Rd, Gampola 20500 &amp; No. 130/A, D. S. Senanayake Veediya, Kandy</p>
          <p class="desk-card__links">
            <a href="tel:+94714041369">+94 71 404 1369</a> ·
            <a href="tel:+94812227417">+94 81 222 7417</a>
          </p>
        </div>

        <div class="desk-card">
          <span class="desk-card__tag">Wholesale &amp; Home Delivery Coordinators</span>
          <h3>Direct Distribution Dispatch</h3>
          <p>Rapid handset, broadband dongle/router, and reload dispatch across Colombo &amp; Kandy corridors.</p>
          <p class="desk-card__links">
            <a href="tel:+94718728879">+94 71 872 8879</a> ·
            <a href="tel:+94718728874">+94 71 872 8874</a> ·
            <a href="tel:+94718728882">+94 71 872 8882</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Right Column: Corporate & Dealer Form -->
    <div class="contact-layout__form">
      <div class="form-panel">
        <span class="eyebrow">Official Inquiry Ledger</span>
        <h3>Send a Corporate or Trade Message</h3>
        <p class="form-panel__sub">
          Complete the form below and receive an instant reference number for follow-up.
        </p>

        <?php if ($formSuccess !== null): ?>
          <div class="alert alert--success" role="status">
            <strong>Inquiry Registered: <?= e($formSuccess['ref_code']) ?></strong>
            <p><?= e($formSuccess['status']) ?> (<?= e($formSuccess['created_at']) ?>)</p>
          </div>
        <?php elseif ($formError !== null): ?>
          <div class="alert alert--error" role="alert">
            <p><?= e($formError) ?></p>
          </div>
        <?php endif; ?>

        <div id="contact-ajax-feedback" aria-live="polite"></div>

        <form method="post" action="contact.php" id="contact-form" class="corp-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

          <div class="form-field">
            <label for="inquiry_type">Nature of Inquiry *</label>
            <select id="inquiry_type" name="inquiry_type" required>
              <?php foreach ($cleanTypes as $t): ?>
                <option value="<?= e($t) ?>" <?= strcasecmp($preselectedType, $t) === 0 ? 'selected' : '' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="full_name">Your Full Name *</label>
              <input type="text" id="full_name" name="full_name" required placeholder="e.g. Nimal Jayawardena">
            </div>
            <div class="form-field">
              <label for="company_name">Organization / Retail Shop Name</label>
              <input type="text" id="company_name" name="company_name" placeholder="Optional (for B2B / Dealers)">
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="phone">Telephone / WhatsApp *</label>
              <input type="tel" id="phone" name="phone" required placeholder="+94 71 XXX XXXX">
            </div>
            <div class="form-field">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" required placeholder="you@company.lk">
            </div>
          </div>

          <div class="form-field">
            <label for="preferred_branch">Preferred Regional Office / Showroom</label>
            <select id="preferred_branch" name="preferred_branch">
              <?php foreach ($branches as $b): ?>
                <option value="<?= e($b['name']) ?>" <?= strcasecmp($preselectedBranch, $b['name']) === 0 ? 'selected' : '' ?>>
                  <?= e($b['name']) ?> (<?= e($b['city']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-field">
            <label for="message">Requirement Details *</label>
            <textarea
              id="message"
              name="message"
              rows="4"
              required
              placeholder="Specify handset models, SLT-Mobitel package needs, mPOS retail shop address, or corporate inquiry details..."
            ></textarea>
          </div>

          <button type="submit" class="btn btn--primary btn--full">Submit Official Inquiry</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
