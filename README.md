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
├── api.php                 # JSON API for inquiries, HR applications & reference lookup
├── router.php              # Built-in PHP server router protecting /includes & /storage
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
