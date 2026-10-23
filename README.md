# Softrings International (Pvt) Ltd — Corporate Profile Website

Official corporate profile web application for **Softrings International (Pvt) Ltd** (Member of the **Softrings Group of Companies**, Sri Lanka), built with **PHP 8+**, semantic **HTML5**, bespoke **CSS3**, and zero-dependency **Vanilla JavaScript**.

## Key Features
- **Authentic Sri Lankan Corporate Data:** Verified group history (Est. 2000 Group / 11 September 2009 Softrings International), subsidiaries matrix, Board of Directors & executive management, and 8 core showrooms/offices across Western and Central Provinces.
- **Live Sri Lanka Standard Time (`Asia/Colombo`) Telemetry:** Server-side PHP + client-side JS real-time clock and automatic per-branch Open/Closed status calculation.
- **Trilingual Identity Ribbon:** Instant switcher across **English**, **සිංහල (Sinhala)**, and **தமிழ் (Tamil)** with `localStorage` persistence.
- **Interactive Sri Lanka SVG Branch Explorer (`branches.php`):** Filter by province, search by city/service, filter by currently open branches, and click nodes on the Sri Lanka map.
- **Softrings mPOS & Sub-Dealer Margin Estimator (`services.php`):** Interactive LKR (`Rs.`) monthly retail revenue calculator for prospective sub-dealers and mPOS merchants.
- **Full PHP + SQLite3 Persistence (`api.php`, `contact.php`, `careers.php`):** CSRF-protected corporate/dealer inquiry desk and HR recruitment portal with automatic SQLite3 initialization (and JSON fallback) plus real-time Reference Code (`SFT-YYYY-XXXXX` / `HR-SFT-XXXXX`) lookup modal.
- **Print-Ready Corporate Dossier:** Built-in `@media print` stylesheet for exporting a clean PDF summary.

## Project Structure
```text
├── index.php               # Executive overview & corporate masthead
├── about.php               # Group heritage, timeline, subsidiaries & leadership
├── services.php            # Business divisions & interactive mPOS LKR estimator
├── branches.php            # Interactive Sri Lanka map & branch directory
├── careers.php             # "The Human Factor" careers & HR application portal
├── contact.php             # Corporate, wholesale & sub-dealer inquiry desk
├── 404.php                 # Branded "page not found" route (exported as 404.html)
├── api.php                 # JSON API for inquiries, HR applications & reference lookup
├── build-pages.php         # Static compiler for GitHub Pages (renders PHP -> HTML)
├── router.php              # Built-in PHP server router protecting /includes & /storage
├── scripts/
│   └── render-page.php     # Isolated CLI page renderer used by the static compiler
├── .github/workflows/
│   └── pages.yml           # GitHub Pages build & deploy workflow
├── includes/
│   ├── config.php          # Central corporate data, SLST branch status & CSRF helpers
│   ├── db.php              # Self-initializing SQLite3 + JSON fallback storage layer
│   ├── header.php          # SEO/JSON-LD head, SLST utility bar & sticky navigation
│   └── footer.php          # Multi-office corporate footer & reference lookup modal
├── assets/
│   ├── css/style.css       # Custom responsive corporate stylesheet + print rules
│   ├── js/main.js          # Vanilla ES6+ client interactivity & progressive AJAX
│   └── images/             # Custom corporate photography & SVG brand emblem
└── COMPANY_PROFILE.md      # Complete researched dossier on Softrings International
```

## Running Locally
Start with PHP's built-in web server from the repository root:
```bash
php -S 0.0.0.0:8080 router.php
```
Or deploy directly to Apache (`.htaccess` included) or Nginx + PHP-FPM (`PHP 8.0+`).

## Publishing with GitHub Pages (Live Static Deployment)
The repository includes a ready-to-run **GitHub Pages** pipeline that publishes the public website automatically on every push to `main`.

### How it works
1. `build-pages.php` renders every PHP page through an isolated PHP CLI process and rewrites all internal routes (`*.php` → `*.html`) into a static build inside `_site/`.
2. `.github/workflows/pages.yml` compiles that static build on **Ubuntu + PHP 8.3**, verifies the output (pages, assets, branded `404.html`, `sitemap.xml`, `robots.txt`), uploads the Pages artifact, and deploys it with `actions/deploy-pages`.
3. The workflow checks that Pages is enabled, configures Pages, and publishes to `https://<owner>.github.io/<repository>/`.

### One-time setup (required before the first deploy)
GitHub does **not** allow an Actions workflow token (`GITHUB_TOKEN`) to create a Pages site — site creation is a repository-administration action. A repository admin must therefore enable Pages **once**:

1. Open **Settings → Pages** in the GitHub repository.
2. Under **Build and deployment**, set **Source** to **GitHub Actions**.
3. Re-run the **Deploy static site to GitHub Pages** workflow (or push to `main`).

After that one-time step, every push to `main` builds and deploys automatically with no further setup and no stored secrets.

### Build locally
```bash
php build-pages.php                # static build into ./_site
php build-pages.php docs           # custom output directory
python3 -m http.server 8099 -d _site   # preview the static build
```

### Static vs. PHP hosting
| Capability | PHP hosting (Apache/Nginx/PHP-FPM) | GitHub Pages (static) |
| --- | --- | --- |
| All pages, styling, map, calculator, trilingual switcher | ✅ | ✅ |
| Inquiry & HR forms | ✅ CSRF-validated, saved to SQLite3/JSON | ✅ Recorded in the browser ledger with a reference code + pre-filled email hand-off |
| Server-side reference lookup (`SFT-…` / `HR-SFT-…`) | ✅ Across all visitors | ✅ For references created in the same browser |

> GitHub Pages cannot execute PHP or write to SQLite, so the static build gracefully degrades the form/API layer to a client-side ledger plus a `mailto:` hand-off to `international@softrings.lk` / `hr@softrings.lk`. Deploy the PHP build to any standard PHP host for the full server-side desk.
