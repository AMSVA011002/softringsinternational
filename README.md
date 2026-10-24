# Softrings International (Pvt) Ltd — Corporate Profile Website

**Live site (GitHub Pages):** https://amsva011002.github.io/softringsinternational/

Official corporate profile web application for **Softrings International (Pvt) Ltd** (Member of the **Softrings Group of Companies**, Sri Lanka), built with **PHP 8+**, semantic **HTML5**, bespoke **CSS3**, and zero-dependency **Vanilla JavaScript**.

## Visual Identity (SLT-Mobitel palette)
The interface palette is taken directly from the official SLT-Mobitel stylesheet (`www.mobitel.lk/sites/all/themes/mobitel/css/style.css`) so the site reads as an authentic Authorized Dealer property:

| Token | Hex | Role |
| --- | --- | --- |
| Mobitel Green | `#3AC22A` | Primary accent, CTAs, active states, brand highlights |
| Mobitel Blue | `#0055AB` | Brand blue, section headings, links, secondary actions |
| Mobitel Navy | `#002E87` | Top bar, hero gradients, footer, deep headings |
| Mobitel Blue 700 | `#004387` | Gradient mid-tones and hover states |
| Mobitel Sky | `#8CD8FF` | Highlights and links on dark surfaces |
| Body / muted / borders | `#3A3A3A` · `#6A6A6A` · `#CCCCCC` | Copy, captions, form outlines |

UI/UX refinements built on that palette: tracked uppercase navigation with a green underline indicator, full-bleed hero slider, overlapping corporate stat bar, icon feature grid, filterable gallery with lightbox, team cards with profile modal, dark milestone cards, partnership plan cards, FAQ accordion, dark footer, interactive map with a green-to-blue coastline, branded range sliders, unified focus rings, `prefers-reduced-motion` support, and a responsive nav that collapses at 1040px with a branded dropdown panel.

### Layout system (Atrium-derived)

The page architecture follows the **QuanticaLabs "Atrium"** corporate one-page template — section order, centred uppercase headings with a 4px accent underline, an 80px section rhythm, a 1140px centred column, square uppercase buttons, and the hero → about → services → work → team → updates → plans → capabilities → FAQ → contact sequence. The template's own green/charcoal palette is **not** used: every accent resolves to the SLT-Mobitel tokens above.

| Layer | File | Purpose |
| --- | --- | --- |
| Base components | `assets/css/style.css` | Tokens, forms, map, calculator, print dossier |
| Atrium layout | `assets/css/atrium.css` | Headings, hero slider, gallery, team, plans, FAQ, footer |

Interactive behaviour lives in `assets/js/main.js`: hero slider (6.5s auto-advance, pause on hover/focus, `inert` slides), gallery filtering, keyboard-accessible lightbox, board-member modal, and a single-open FAQ accordion.

### Branch map

`branches.php` renders a real Sri Lanka **district map** (all 25 districts) instead of a stylised silhouette:

- Geometry: `includes/sri-lanka-districts.php`, derived from [`@svg-maps/sri-lanka`](https://github.com/VictorCazanave/svg-maps) v2.0.0 (CC BY 4.0, based on [MapSVG](https://mapsvg.com/maps/sri-lanka)), drawn in a `0 0 450 793` viewBox.
- Pins: every branch in `includes/config.php` carries its real `lat`/`lng`, projected onto that viewBox by `sri_lanka_map_point()`. The helper applies a measured calibration offset so all eight coordinates fall inside their own district polygon (verified with `isPointInFill`).
- Western and Central districts are tinted, districts that hold a branch are highlighted, and pin colour still reflects the live SLST open/closed status.

### Placeholder staff portraits

Board directors and functional managers currently use generated placeholder portraits in `assets/images/team/` (monogram + "photo to follow" on a brand gradient). Each person's `photo` path is stored in `includes/config.php`, so replacing a real photograph only requires overwriting the file or changing that one key.

### Sinhala translation

Every visible string is catalogued in `assets/js/i18n-si.js` as `English → Sinhala`. `main.js` (§3) applies it in three
layers: whole-block templates (`{0}`, `{1}` slots) where a sentence is split by a link or `<strong>`, plain text nodes,
and translatable attributes (`placeholder`, `aria-label`, `title`, `alt`, meta description, `<title>`). Switching back
to English restores the captured originals, so repeated toggles never drift. Time-dependent labels (the SLST clock,
"open now" pills, tracker results, calculator units) are produced by `SR.t()` in `main.js` so they stay translated
between the 30-second telemetry refreshes.

Sinhala typography is handled in `atrium.css` §19: the `--font-sans` / `--font-serif` / `--font-mono` tokens are
re-pointed at Noto Sans/Serif Sinhala, uppercase tracking is relaxed, and buttons/inputs inherit the page typeface.

### Scroll-to-top control

`includes/footer.php` ships a floating back-to-top button that appears after 420px of scrolling, draws a circular reading-progress ring, and smooth-scrolls to the top (respecting `prefers-reduced-motion`). It is keyboard focusable and hidden in print.

## Key Features
- **Authentic Sri Lankan Corporate Data:** Verified group history (Est. 2000 Group / 11 September 2009 Softrings International), subsidiaries matrix, Board of Directors & executive management, and 8 core showrooms/offices across Western and Central Provinces.
- **Live Sri Lanka Standard Time (`Asia/Colombo`) Telemetry:** Server-side PHP + client-side JS real-time clock and automatic per-branch Open/Closed status calculation.
- **Bilingual Site (English / සිංහල):** The header switcher translates the *entire* site — navigation, page copy, branch records, forms, validation messages, modals and page titles. The catalogue lives in `assets/js/i18n-si.js` and the choice is remembered in `localStorage`, so every page you open afterwards loads in Sinhala.
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
│   ├── sri-lanka-districts.php  # 25-district map geometry (CC BY 4.0, MapSVG)
│   ├── header.php          # SEO/JSON-LD head, SLST utility bar & sticky navigation
│   └── footer.php          # Footer, scroll-to-top control & reference lookup modal
├── assets/
│   ├── css/style.css       # Design tokens, forms, map shell + print rules
│   ├── css/atrium.css      # Atrium layout system, map styling, scroll-to-top
│   ├── js/main.js          # Vanilla ES6+ client interactivity & progressive AJAX
│   └── images/             # Corporate photography, brand emblem & team/ placeholders
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
| All pages, styling, map, calculator, English/Sinhala switcher | ✅ | ✅ |
| Inquiry & HR forms | ✅ CSRF-validated, saved to SQLite3/JSON | ✅ Recorded in the browser ledger with a reference code + pre-filled email hand-off |
| Server-side reference lookup (`SFT-…` / `HR-SFT-…`) | ✅ Across all visitors | ✅ For references created in the same browser |

> GitHub Pages cannot execute PHP or write to SQLite, so the static build gracefully degrades the form/API layer to a client-side ledger plus a `mailto:` hand-off to `international@softrings.lk` / `hr@softrings.lk`. Deploy the PHP build to any standard PHP host for the full server-side desk.
