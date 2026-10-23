<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Careers — The Human Factor';
$pageDescription = 'Build your career with Softrings International (Pvt) Ltd in Colombo, Maharagama, Homagama, Kandy, and Gampola. Explore sales, IT, accounts, technical, and customer care roles.';
$activeNav = 'careers';

$openings = get_career_openings();
$formSuccess = null;
$formError = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!verify_csrf((string) ($_POST['csrf_token'] ?? ''))) {
        $formError = 'Security session expired. Please submit your application again.';
    } else {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $jobSelected = trim((string) ($_POST['job_selection'] ?? 'SFT-HR-01|Branch Sales & Territory Manager'));
        [$jobCode, $jobTitle] = array_pad(explode('|', $jobSelected, 2), 2, 'General Application');
        $district = trim((string) ($_POST['district'] ?? 'Colombo District'));
        $exp = trim((string) ($_POST['experience_years'] ?? '1-3 Years'));
        $summary = trim((string) ($_POST['summary'] ?? ''));

        if (mb_strlen($fullName) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($summary) < 12) {
            $formError = 'Please complete all required fields with a valid email and brief qualification summary.';
        } else {
            $formSuccess = save_career_application([
                'job_code' => $jobCode,
                'job_title' => $jobTitle,
                'full_name' => $fullName,
                'phone' => $phone,
                'email' => $email,
                'district' => $district,
                'experience_years' => $exp,
                'summary' => $summary,
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
      <span>Careers</span>
    </nav>
    <div class="page-hero__split">
      <div>
        <span class="eyebrow eyebrow--gold">The Human Factor</span>
        <h1>Build Your Career With Softrings International</h1>
        <p class="page-hero__lead">
          As one of Sri Lanka’s established mobile sales and SLT-Mobitel distribution employers, we offer structured career progression across retail management, IT systems, accounting, technical engineering, and field logistics.
        </p>
      </div>
      <div class="dossier-meta-card">
        <div class="dossier-meta-row">
          <span>Human Resources Desk</span>
          <strong>Homagama &amp; Kandy Offices</strong>
        </div>
        <div class="dossier-meta-row">
          <span>HR Direct Email</span>
          <strong><?= e(COMPANY_HR_EMAIL) ?></strong>
        </div>
        <div class="dossier-meta-row">
          <span>Recruitment Districts</span>
          <strong>Colombo &amp; Kandy Districts</strong>
        </div>
        <div class="dossier-meta-row">
          <span>HR Contact Line</span>
          <strong>+94 71 404 1366 / +94 11 289 5061</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container careers-layout">
    <div class="careers-layout__openings">
      <span class="eyebrow">Open Functional Tracks</span>
      <h2 class="section-title">Current Career Opportunities</h2>
      <p class="prose-text">
        Select any role below to pre-fill the Human Resources application portal, or submit a general profile to our Colombo or Kandy recruitment teams.
      </p>

      <div class="jobs-stack">
        <?php foreach ($openings as $job): ?>
          <article class="job-card">
            <div class="job-card__head">
              <div>
                <span class="mono-badge"><?= e($job['code']) ?></span>
                <span class="job-dept"><?= e($job['department']) ?></span>
                <h3><?= e($job['title']) ?></h3>
              </div>
              <button
                type="button"
                class="btn btn--secondary btn--sm"
                data-select-job="<?= e($job['code'] . '|' . $job['title']) ?>"
              >Apply Now</button>
            </div>
            <p class="job-card__summary"><?= e($job['summary']) ?></p>
            <div class="job-card__meta">
              <span><strong>Locations:</strong> <?= e($job['locations']) ?></span>
              <span><strong>Schedule:</strong> <?= e($job['type']) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <aside class="careers-layout__form" id="hr-application-box">
      <div class="form-panel">
        <span class="eyebrow">Human Resources Portal</span>
        <h3>Submit Candidate Profile</h3>
        <p class="form-panel__sub">
          Applications are logged directly with a trackable <code>HR-SFT</code> reference code, or you may email your full CV to <a href="mailto:<?= e(COMPANY_HR_EMAIL) ?>"><?= e(COMPANY_HR_EMAIL) ?></a>.
        </p>

        <?php if ($formSuccess !== null): ?>
          <div class="alert alert--success" role="status">
            <strong>Application Logged: <?= e($formSuccess['ref_code']) ?></strong>
            <p><?= e($formSuccess['status']) ?> (<?= e($formSuccess['created_at']) ?>)</p>
          </div>
        <?php elseif ($formError !== null): ?>
          <div class="alert alert--error" role="alert">
            <p><?= e($formError) ?></p>
          </div>
        <?php endif; ?>

        <div id="career-ajax-feedback" aria-live="polite"></div>

        <form method="post" action="careers.php#hr-application-box" id="career-form" class="corp-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

          <div class="form-field">
            <label for="job_selection">Position Applying For *</label>
            <select id="job_selection" name="job_selection" required>
              <?php foreach ($openings as $job):
                  $val = $job['code'] . '|' . $job['title'];
              ?>
                <option value="<?= e($val) ?>"><?= e($job['title']) ?> (<?= e($job['code']) ?>)</option>
              <?php endforeach; ?>
              <option value="SFT-GEN|Management Trainee / General Pool">Management Trainee / General Talent Pool</option>
            </select>
          </div>

          <div class="form-field">
            <label for="cand_name">Full Name *</label>
            <input type="text" id="cand_name" name="full_name" required placeholder="e.g. Kasun Perera">
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="cand_phone">Mobile Number *</label>
              <input type="tel" id="cand_phone" name="phone" required placeholder="+94 71 XXX XXXX">
            </div>
            <div class="form-field">
              <label for="cand_email">Email Address *</label>
              <input type="email" id="cand_email" name="email" required placeholder="name@example.lk">
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-field">
              <label for="cand_district">Preferred District *</label>
              <select id="cand_district" name="district">
                <option value="Colombo District (Maharagama / Homagama / Nugegoda)">Colombo District (Maharagama / Homagama)</option>
                <option value="Colombo Coastal (Moratuwa)">Colombo South (Moratuwa)</option>
                <option value="Seethawaka (Avissawella)">Seethawaka (Avissawella)</option>
                <option value="Kandy District (Kandy / Peradeniya)">Kandy District (Kandy / Peradeniya)</option>
                <option value="Gampola (Central Province)">Gampola (Central Province)</option>
              </select>
            </div>
            <div class="form-field">
              <label for="cand_exp">Experience Level *</label>
              <select id="cand_exp" name="experience_years">
                <option value="School Leaver / Trainee">School Leaver / Trainee</option>
                <option value="1 - 2 Years">1 – 2 Years</option>
                <option value="3 - 5 Years">3 – 5 Years</option>
                <option value="5+ Years">5+ Years Senior</option>
              </select>
            </div>
          </div>

          <div class="form-field">
            <label for="cand_summary">Qualifications &amp; Career Summary *</label>
            <textarea
              id="cand_summary"
              name="summary"
              rows="4"
              required
              placeholder="Briefly summarize your academic qualifications, telecom/retail experience, languages spoken, or driving/riding license details..."
            ></textarea>
          </div>

          <button type="submit" class="btn btn--primary btn--full">Submit Application to HR</button>
        </form>
      </div>
    </aside>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
