/**
 * Softrings International (Pvt) Ltd — Interactive Corporate Client Script
 * Supports both PHP server mode (api.php + SQLite3) and static GitHub Pages mode seamlessly.
 */

(function () {
  'use strict';

  const STATIC_LEDGER_KEY = 'softrings_ledger_v1';

  /**
   * GitHub Pages (or any host without PHP) cannot execute api.php, so requests
   * would only return a 404 document. Detect static hosting up front and use the
   * browser ledger directly instead of logging noisy failed network calls.
   */
  const HAS_PHP_BACKEND = !/(^|\.)github\.io$/i.test(window.location.hostname)
    && !/\.html?$/i.test(window.location.pathname.split('/').pop() || '');
  const IS_STATIC_HOST = !HAS_PHP_BACKEND;

  const escapeHtml = (str) => {
    const div = document.createElement('div');
    div.textContent = String(str ?? '');
    return div.innerHTML;
  };

  const readLocalLedger = () => {
    try {
      const raw = localStorage.getItem(STATIC_LEDGER_KEY);
      return raw ? JSON.parse(raw) : {};
    } catch (_) {
      return {};
    }
  };

  const writeLocalLedger = (ref, record) => {
    try {
      const all = readLocalLedger();
      all[String(ref).toUpperCase()] = record;
      localStorage.setItem(STATIC_LEDGER_KEY, JSON.stringify(all));
    } catch (_) {}
  };

  const generateRefCode = (prefix) => {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let token = '';
    for (let i = 0; i < 5; i++) {
      token += chars[Math.floor(Math.random() * chars.length)];
    }
    return `${prefix}-${token}`;
  };

  const getColomboNowParts = () => {
    const now = new Date();
    const dtf = new Intl.DateTimeFormat('en-US', {
      timeZone: 'Asia/Colombo',
      weekday: 'long',
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false,
    });
    const parts = {};
    dtf.formatToParts(now).forEach((p) => {
      parts[p.type] = p.value;
    });
    const hour24 = Number(parts.hour === '24' ? 0 : parts.hour);
    const minute = Number(parts.minute || 0);
    const weekdayMap = {
      Monday: 1,
      Tuesday: 2,
      Wednesday: 3,
      Thursday: 4,
      Friday: 5,
      Saturday: 6,
      Sunday: 7,
    };
    const dow = weekdayMap[parts.weekday] || 1;
    const display12 = new Intl.DateTimeFormat('en-US', {
      timeZone: 'Asia/Colombo',
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,
    }).format(now);

    return {
      dow,
      weekdayName: parts.weekday || 'Today',
      minutesOfDay: hour24 * 60 + minute,
      display12,
      timestampSlst: `${parts.year}-${parts.month}-${parts.day} ${String(hour24).padStart(2, '0')}:${parts.minute}:${parts.second} SLST`,
    };
  };

  const formatTime12 = (hhmm) => {
    const [hStr, mStr] = String(hhmm).split(':');
    const h = Number(hStr);
    const m = Number(mStr || 0);
    const suffix = h >= 12 ? 'PM' : 'AM';
    const h12 = h % 12 === 0 ? 12 : h % 12;
    return `${h12}:${String(m).padStart(2, '0')} ${suffix}`;
  };

  const computeBranchStatusClient = (sched, colombo) => {
    let win = null;
    if (colombo.dow >= 1 && colombo.dow <= 5) win = sched.mf;
    else if (colombo.dow === 6) win = sched.sat;
    else win = sched.sun;

    if (!win || !Array.isArray(win)) {
      return { isOpen: false, label: 'Closed Today (Opens Mon)' };
    }
    const [oH, oM] = win[0].split(':').map(Number);
    const [cH, cM] = win[1].split(':').map(Number);
    const openMins = oH * 60 + oM;
    const closeMins = cH * 60 + cM;

    if (colombo.minutesOfDay >= openMins && colombo.minutesOfDay < closeMins) {
      return { isOpen: true, label: `Open Now · Closes ${formatTime12(win[1])}` };
    }
    if (colombo.minutesOfDay < openMins) {
      return { isOpen: false, label: `Opens Today at ${formatTime12(win[0])}` };
    }
    return { isOpen: false, label: 'Closed for Today' };
  };

  /* 1. Mobile Navigation Drawer */
  const navToggle = document.getElementById('nav-toggle');
  const primaryNav = document.getElementById('primary-nav');

  if (navToggle && primaryNav) {
    navToggle.addEventListener('click', () => {
      const expanded = navToggle.getAttribute('aria-expanded') === 'true';
      navToggle.setAttribute('aria-expanded', String(!expanded));
      primaryNav.classList.toggle('is-open', !expanded);
    });
  }

  /* 2. Live Sri Lanka Standard Time (Asia/Colombo) Clock & Dynamic Branch Telemetry */
  const slstClock = document.getElementById('slst-clock');
  const liveBadge = document.getElementById('live-indicator-badge');
  const utilitySummary = document.getElementById('utility-open-summary');
  const branchesLocalTime = document.getElementById('branches-local-time');

  let parsedSchedules = [];
  if (utilitySummary && utilitySummary.dataset.schedules) {
    try {
      parsedSchedules = JSON.parse(utilitySummary.dataset.schedules) || [];
    } catch (_) {}
  }

  const refreshColomboTelemetry = () => {
    try {
      const colombo = getColomboNowParts();
      if (slstClock) {
        slstClock.textContent = `Colombo ${colombo.display12} SLST`;
      }
      if (branchesLocalTime) {
        branchesLocalTime.textContent = `${colombo.weekdayName}, ${colombo.display12}`;
      }

      if (parsedSchedules.length > 0) {
        let openCount = 0;
        parsedSchedules.forEach((sched) => {
          const st = computeBranchStatusClient(sched, colombo);
          if (st.isOpen) openCount++;

          const miniPill = document.querySelector(`[data-mini-status="${sched.id}"]`);
          if (miniPill) {
            miniPill.textContent = st.isOpen ? 'Open' : 'Closed';
            miniPill.classList.toggle('is-open', st.isOpen);
            miniPill.classList.toggle('is-closed', !st.isOpen);
          }

          const fullPill = document.querySelector(`[data-full-status="${sched.id}"]`);
          if (fullPill) {
            fullPill.textContent = st.label;
            fullPill.classList.toggle('is-open', st.isOpen);
            fullPill.classList.toggle('is-closed', !st.isOpen);
          }

          const card = document.getElementById(sched.id);
          if (card && card.classList.contains('branch-card')) {
            card.dataset.open = st.isOpen ? '1' : '0';
          }

          const mapPin = document.querySelector(`.map-pin[data-target-branch="${sched.id}"]`);
          if (mapPin) {
            mapPin.classList.toggle('is-open', st.isOpen);
          }
        });

        if (liveBadge) {
          liveBadge.classList.toggle('is-open', openCount > 0);
          liveBadge.classList.toggle('is-standby', openCount === 0);
        }
        if (utilitySummary) {
          utilitySummary.innerHTML = openCount > 0
            ? `<strong>${openCount} of ${parsedSchedules.length}</strong> Regional Hubs Open Now`
            : 'Standard Hours Resume 8:00 AM SLST · Digital Inquiry Desk Active';
        }
      }
    } catch (_) {
      // Graceful fallback keeps server/build-time values
    }
  };

  refreshColomboTelemetry();
  setInterval(refreshColomboTelemetry, 30000);

  /* 3. Trilingual Identity Ribbon Switcher (EN / සිං / தமிழ்) */
  const langButtons = document.querySelectorAll('[data-lang-switch]');
  const ribbonMotto = document.getElementById('ribbon-motto');
  const ribbonBadge = document.getElementById('ribbon-badge');
  const brandCorpName = document.getElementById('brand-corp-name');

  const applyLanguage = (btn) => {
    if (!btn) return;
    const code = btn.getAttribute('data-lang-switch') || 'en';
    langButtons.forEach((b) => {
      const active = b === btn;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-pressed', String(active));
    });
    if (ribbonMotto && btn.dataset.motto) {
      ribbonMotto.textContent = btn.dataset.motto;
    }
    if (ribbonBadge && btn.dataset.badge) {
      ribbonBadge.textContent = btn.dataset.badge;
    }
    if (brandCorpName && btn.dataset.corpName) {
      brandCorpName.textContent = code === 'en' ? 'Softrings International' : btn.dataset.corpName;
    }
    document.documentElement.setAttribute('data-lang', code);
    try {
      localStorage.setItem('softrings_lang', code);
    } catch (_) {}
  };

  if (langButtons.length > 0) {
    langButtons.forEach((btn) => {
      btn.addEventListener('click', () => applyLanguage(btn));
    });
    try {
      const saved = localStorage.getItem('softrings_lang');
      if (saved) {
        const match = document.querySelector(`[data-lang-switch="${saved}"]`);
        if (match) applyLanguage(match);
      }
    } catch (_) {}
  }

  /* 3b. Query-String Preselection on Contact Page (Static & Dynamic Parity) */
  try {
    const params = new URLSearchParams(window.location.search);
    const qsType = params.get('type');
    const qsBranch = params.get('branch');
    const typeSelect = document.getElementById('inquiry_type');
    const branchSelect = document.getElementById('preferred_branch');

    if (qsType && typeSelect) {
      Array.from(typeSelect.options).forEach((opt) => {
        if (opt.value.toLowerCase() === qsType.toLowerCase()) {
          typeSelect.value = opt.value;
        }
      });
    }
    if (qsBranch && branchSelect) {
      Array.from(branchSelect.options).forEach((opt) => {
        if (opt.value.toLowerCase() === qsBranch.toLowerCase()) {
          branchSelect.value = opt.value;
        }
      });
    }
  } catch (_) {}

  /* 4. Reference Status Tracker Modal (Supports PHP API + Static Ledger) */
  const trackerModal = document.getElementById('tracker-modal');
  const trackerForm = document.getElementById('tracker-form');
  const trackerInput = document.getElementById('tracker-ref-input');
  const trackerResult = document.getElementById('tracker-result');

  const openTracker = (prefillRef = '') => {
    if (!trackerModal) return;
    trackerModal.hidden = false;
    trackerModal.setAttribute('aria-hidden', 'false');
    if (trackerInput) {
      if (prefillRef) trackerInput.value = prefillRef;
      setTimeout(() => trackerInput.focus(), 40);
    }
  };

  const closeTracker = () => {
    if (!trackerModal) return;
    trackerModal.hidden = true;
    trackerModal.setAttribute('aria-hidden', 'true');
  };

  document.querySelectorAll('[data-open-tracker]').forEach((el) => {
    el.addEventListener('click', () => openTracker());
  });

  document.querySelectorAll('[data-close-tracker]').forEach((el) => {
    el.addEventListener('click', closeTracker);
  });

  if (trackerModal) {
    trackerModal.addEventListener('click', (e) => {
      if (e.target === trackerModal) closeTracker();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeTracker();
      if (primaryNav && primaryNav.classList.contains('is-open')) {
        primaryNav.classList.remove('is-open');
        if (navToggle) navToggle.setAttribute('aria-expanded', 'false');
      }
    }
  });

  const renderRecordAlert = (rec) => `
    <div class="alert alert--success">
      <strong>${escapeHtml(rec.record_kind || 'Record')}: ${escapeHtml(rec.ref_code)}</strong>
      <p><strong>Applicant / Contact:</strong> ${escapeHtml(rec.full_name)}</p>
      <p><strong>Subject:</strong> ${escapeHtml(rec.subject || rec.inquiry_type || rec.job_title || '')}</p>
      <p><strong>Status:</strong> ${escapeHtml(rec.status)}</p>
      <p><strong>Logged:</strong> ${escapeHtml(rec.created_at)}</p>
    </div>
  `;

  if (trackerForm && trackerInput && trackerResult) {
    trackerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const ref = trackerInput.value.trim().toUpperCase();
      if (!ref) return;
      trackerResult.innerHTML = '<div class="alert">Checking Softrings Corporate Desk ledger...</div>';

      if (!IS_STATIC_HOST) {
        try {
          const res = await fetch(`api.php?action=ticket&ref=${encodeURIComponent(ref)}`);
          const text = await res.text();
          if (!text.trim().startsWith('<?php')) {
            const data = JSON.parse(text);
            if (res.ok && data && data.ok && data.record) {
              trackerResult.innerHTML = renderRecordAlert(data.record);
              return;
            }
            if (res.ok) {
              const localRec = readLocalLedger()[ref];
              if (localRec) {
                trackerResult.innerHTML = renderRecordAlert(localRec);
                return;
              }
              trackerResult.innerHTML = `<div class="alert alert--error"><p>${escapeHtml((data && data.error) || 'Reference not found.')}</p></div>`;
              return;
            }
          }
        } catch (_) {
          // Fall through to client static ledger
        }
      }

      const localRec = readLocalLedger()[ref];
      if (localRec) {
        trackerResult.innerHTML = renderRecordAlert(localRec);
      } else {
        trackerResult.innerHTML = `<div class="alert alert--error"><p>No inquiry or HR application matched reference "${escapeHtml(ref)}".</p></div>`;
      }
    });
  }

  /* 5. Interactive Softrings mPOS & Sub-Dealer Margin Estimator (services.php) */
  const reloadInput = document.getElementById('calc-reloads');
  const simInput = document.getElementById('calc-sims');
  const phoneInput = document.getElementById('calc-phones');

  if (reloadInput && simInput && phoneInput) {
    const outReloads = document.getElementById('out-reloads');
    const outSims = document.getElementById('out-sims');
    const outPhones = document.getElementById('out-phones');
    const resMpos = document.getElementById('res-mpos');
    const resSims = document.getElementById('res-sims');
    const resDevices = document.getElementById('res-devices');
    const resTotal = document.getElementById('res-total');

    const formatLkr = (n) => 'Rs. ' + Math.round(n).toLocaleString('en-LK');

    const recalculateMpos = () => {
      const dailyReload = Number(reloadInput.value || 0);
      const monthlySims = Number(simInput.value || 0);
      const monthlyPhones = Number(phoneInput.value || 0);

      if (outReloads) outReloads.textContent = `${formatLkr(dailyReload)} / day`;
      if (outSims) outSims.textContent = `${monthlySims} connections`;
      if (outPhones) outPhones.textContent = `${monthlyPhones} units`;

      const mposCommission = dailyReload * 30 * 0.045;
      const simIncentive = monthlySims * 350;
      const deviceMargin = monthlyPhones * 2800;
      const total = mposCommission + simIncentive + deviceMargin;

      if (resMpos) resMpos.textContent = formatLkr(mposCommission);
      if (resSims) resSims.textContent = formatLkr(simIncentive);
      if (resDevices) resDevices.textContent = formatLkr(deviceMargin);
      if (resTotal) resTotal.textContent = formatLkr(total);
    };

    [reloadInput, simInput, phoneInput].forEach((input) => {
      input.addEventListener('input', recalculateMpos);
    });
    recalculateMpos();
  }

  /* 6. Interactive Branch Explorer & Sri Lanka SVG Network Map (branches.php) */
  const branchCards = Array.from(document.querySelectorAll('.branch-card'));
  const provincePills = document.querySelectorAll('[data-province-filter]');
  const searchInput = document.getElementById('branch-search-input');
  const openOnlyCheckbox = document.getElementById('branch-open-only');
  const emptyState = document.getElementById('branch-empty-state');
  const mapPins = document.querySelectorAll('.map-pin');

  if (branchCards.length > 0) {
    let activeProvince = 'all';

    const applyBranchFilters = () => {
      const q = (searchInput ? searchInput.value : '').trim().toLowerCase();
      const openOnly = Boolean(openOnlyCheckbox && openOnlyCheckbox.checked);
      let visibleCount = 0;

      branchCards.forEach((card) => {
        const matchesProvince = activeProvince === 'all' || card.dataset.province === activeProvince;
        const matchesQuery = !q || (card.dataset.search || '').includes(q);
        const matchesOpen = !openOnly || card.dataset.open === '1';
        const show = matchesProvince && matchesQuery && matchesOpen;
        card.hidden = !show;
        if (show) visibleCount++;
      });

      if (emptyState) {
        emptyState.hidden = visibleCount > 0;
      }
    };

    provincePills.forEach((pill) => {
      pill.addEventListener('click', () => {
        activeProvince = pill.getAttribute('data-province-filter') || 'all';
        provincePills.forEach((p) => p.classList.toggle('is-active', p === pill));
        applyBranchFilters();
      });
    });

    if (searchInput) searchInput.addEventListener('input', applyBranchFilters);
    if (openOnlyCheckbox) openOnlyCheckbox.addEventListener('change', applyBranchFilters);

    const highlightBranch = (branchId, scrollIntoView = false) => {
      branchCards.forEach((c) => c.classList.toggle('is-highlighted', c.id === branchId));
      mapPins.forEach((p) => p.classList.toggle('is-selected', p.getAttribute('data-target-branch') === branchId));
      if (scrollIntoView) {
        const target = document.getElementById(branchId);
        if (target) {
          target.hidden = false;
          target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    };

    mapPins.forEach((pin) => {
      const targetId = pin.getAttribute('data-target-branch');
      pin.addEventListener('click', () => highlightBranch(targetId, true));
      pin.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          highlightBranch(targetId, true);
        }
      });
    });

    branchCards.forEach((card) => {
      card.addEventListener('mouseenter', () => highlightBranch(card.id, false));
    });
  }

  /* 7. Career Role Quick-Select & Dual-Mode Forms (PHP Server + GitHub Pages Static Fallback) */
  const jobSelectDropdown = document.getElementById('job_selection');
  document.querySelectorAll('[data-select-job]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const val = btn.getAttribute('data-select-job');
      if (jobSelectDropdown && val) {
        jobSelectDropdown.value = val;
        const formBox = document.getElementById('hr-application-box');
        if (formBox) formBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const nameField = document.getElementById('cand_name');
        if (nameField) setTimeout(() => nameField.focus(), 300);
      }
    });
  });

  const validateClientPayload = (payload, isCareer) => {
    if (!payload.full_name || String(payload.full_name).trim().length < 2) {
      return 'Please enter your full name.';
    }
    if (!/^[0-9+\-\s()]{7,20}$/.test(String(payload.phone || '').trim())) {
      return 'Please provide a valid Sri Lankan or international phone number.';
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(payload.email || '').trim())) {
      return 'Please provide a valid email address.';
    }
    const textBody = isCareer ? payload.summary : payload.message;
    if (!textBody || String(textBody).trim().length < 10) {
      return 'Please include brief details (at least 10 characters).';
    }
    return null;
  };

  const renderSuccessBanner = (feedback, data, fallbackMailto) => {
    const mailBtn = fallbackMailto
      ? `<a href="${escapeHtml(fallbackMailto)}" class="btn btn--secondary btn--sm">Send Direct Copy via Email</a>`
      : '';
    feedback.innerHTML = `
      <div class="alert alert--success" role="status">
        <strong>Reference Code: ${escapeHtml(data.ref_code)}</strong>
        <p>${escapeHtml(data.message)}</p>
        <p><small>${escapeHtml(data.status)} · ${escapeHtml(data.created_at)}</small></p>
        <p style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
          <button type="button" class="btn btn--secondary btn--sm" data-verify-now="${escapeHtml(data.ref_code)}">
            Verify Reference in Live Tracker
          </button>
          ${mailBtn}
        </p>
      </div>
    `;
    const verifyBtn = feedback.querySelector('[data-verify-now]');
    if (verifyBtn) {
      verifyBtn.addEventListener('click', () => {
        openTracker(verifyBtn.getAttribute('data-verify-now') || '');
      });
    }
  };

  const bindDualModeForm = (formId, feedbackId, endpoint, isCareer, transformPayload) => {
    const form = document.getElementById(formId);
    const feedback = document.getElementById(feedbackId);
    if (!form || !feedback) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(form);
      const payload = transformPayload ? transformPayload(fd) : Object.fromEntries(fd.entries());

      const clientErr = validateClientPayload(payload, isCareer);
      if (clientErr) {
        feedback.innerHTML = `<div class="alert alert--error" role="alert"><p>${escapeHtml(clientErr)}</p></div>`;
        return;
      }

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.textContent : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
      }

      try {
        if (!IS_STATIC_HOST) {
          const res = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });
          const text = await res.text();
          if (!text.trim().startsWith('<?php')) {
            const data = JSON.parse(text);
            if (res.ok && data && data.ok) {
              writeLocalLedger(data.ref_code, {
                record_kind: isCareer ? 'Career Application' : 'Corporate / Dealer Inquiry',
                ref_code: data.ref_code,
                full_name: payload.full_name,
                subject: isCareer ? payload.job_title : payload.inquiry_type,
                status: data.status,
                created_at: data.created_at,
              });
              renderSuccessBanner(feedback, data, '');
              form.reset();
              return;
            }
            if (res.status >= 400 && res.status !== 404 && res.status !== 405 && data && data.error) {
              feedback.innerHTML = `<div class="alert alert--error" role="alert"><p>${escapeHtml(data.error)}</p></div>`;
              return;
            }
          }
        }
      } catch (_) {
        // Static hosting (GitHub Pages) fallback below
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      }

      // Static Hosting (GitHub Pages) Instant Ledger Fallback
      const colombo = getColomboNowParts();
      const year = new Date().getFullYear();
      const refCode = isCareer ? generateRefCode('HR-SFT') : generateRefCode(`SFT-${year}`);
      const statusText = isCareer
        ? 'Logged in Portal Ledger — Ready for HR Dispatch (hr@softrings.lk)'
        : 'Logged in Portal Ledger — Routed for Corporate Desk (international@softrings.lk)';
      const subjectLabel = isCareer ? payload.job_title : payload.inquiry_type;

      writeLocalLedger(refCode, {
        record_kind: isCareer ? 'Career Application' : 'Corporate / Dealer Inquiry',
        ref_code: refCode,
        full_name: payload.full_name,
        subject: subjectLabel,
        status: statusText,
        created_at: colombo.timestampSlst,
      });

      const targetEmail = isCareer ? 'hr@softrings.lk' : 'international@softrings.lk';
      const mailSubject = `[${refCode}] ${subjectLabel} — ${payload.full_name}`;
      const mailBody = isCareer
        ? `Reference: ${refCode}\nCandidate: ${payload.full_name}\nPhone: ${payload.phone}\nEmail: ${payload.email}\nDistrict: ${payload.district}\nExperience: ${payload.experience_years}\n\nSummary:\n${payload.summary}`
        : `Reference: ${refCode}\nContact: ${payload.full_name}\nOrganization: ${payload.company_name || 'N/A'}\nPhone: ${payload.phone}\nEmail: ${payload.email}\nBranch: ${payload.preferred_branch}\n\nDetails:\n${payload.message}`;
      const mailtoHref = `mailto:${targetEmail}?subject=${encodeURIComponent(mailSubject)}&body=${encodeURIComponent(mailBody)}`;

      renderSuccessBanner(
        feedback,
        {
          ref_code: refCode,
          message: isCareer
            ? 'Your candidate profile has been recorded with a verifiable HR reference code.'
            : 'Your corporate inquiry has been recorded with a verifiable reference code.',
          status: statusText,
          created_at: colombo.timestampSlst,
        },
        mailtoHref
      );
      form.reset();
    });
  };

  bindDualModeForm('contact-form', 'contact-ajax-feedback', 'api.php?action=contact', false);

  bindDualModeForm('career-form', 'career-ajax-feedback', 'api.php?action=apply', true, (fd) => {
    const raw = Object.fromEntries(fd.entries());
    const parts = String(raw.job_selection || 'SFT-GEN|General Pool').split('|');
    return {
      csrf_token: raw.csrf_token,
      job_code: parts[0] || 'SFT-GEN',
      job_title: parts[1] || 'General Application',
      full_name: raw.full_name,
      phone: raw.phone,
      email: raw.email,
      district: raw.district,
      experience_years: raw.experience_years,
      summary: raw.summary,
    };
  });

  /* 7. Atrium Interface Layer — sticky header, hero slider, gallery, team, FAQ */
  const siteHeader = document.getElementById('site-header');
  if (siteHeader) {
    const syncHeader = () => siteHeader.classList.toggle('is-scrolled', window.scrollY > 10);
    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });
  }

  /* 7a. Full-bleed hero slider */
  const hero = document.querySelector('[data-hero]');
  if (hero) {
    const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(hero.querySelectorAll('[data-hero-dot]'));
    const indexOut = hero.querySelector('[data-hero-index]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let current = 0;
    let timer = null;

    const show = (next) => {
      if (!slides.length) return;
      current = (next + slides.length) % slides.length;
      slides.forEach((slide, i) => {
        const active = i === current;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', String(!active));
        if (active) {
          slide.removeAttribute('inert');
        } else {
          slide.setAttribute('inert', '');
        }
      });
      dots.forEach((dot, i) => {
        const active = i === current;
        dot.classList.toggle('is-active', active);
        dot.setAttribute('aria-pressed', String(active));
      });
      if (indexOut) indexOut.textContent = String(current + 1).padStart(2, '0');
    };

    const stop = () => {
      if (timer) {
        window.clearInterval(timer);
        timer = null;
      }
    };
    const start = () => {
      if (reduceMotion || slides.length < 2) return;
      stop();
      timer = window.setInterval(() => show(current + 1), 6500);
    };

    hero.querySelectorAll('[data-hero-next]').forEach((btn) => {
      btn.addEventListener('click', () => {
        show(current + 1);
        start();
      });
    });
    hero.querySelectorAll('[data-hero-prev]').forEach((btn) => {
      btn.addEventListener('click', () => {
        show(current - 1);
        start();
      });
    });
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        show(i);
        start();
      });
    });

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', (e) => {
      if (!hero.contains(e.relatedTarget)) start();
    });
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stop();
      else start();
    });

    show(0);
    start();
  }

  /* 7b. Filterable gallery + lightbox */
  const gallery = document.querySelector('[data-gallery]');
  if (gallery) {
    const tiles = Array.from(gallery.querySelectorAll('[data-gallery-item]'));
    const filters = Array.from(document.querySelectorAll('[data-gallery-filter]'));
    const lightbox = document.getElementById('atr-lightbox');
    const lightboxImg = lightbox ? lightbox.querySelector('[data-lightbox-img]') : null;
    const lightboxCat = lightbox ? lightbox.querySelector('[data-lightbox-cat]') : null;
    const lightboxCaption = lightbox ? lightbox.querySelector('[data-lightbox-caption]') : null;
    const prevBtn = lightbox ? lightbox.querySelector('[data-lightbox-prev]') : null;
    const nextBtn = lightbox ? lightbox.querySelector('[data-lightbox-next]') : null;
    let visible = tiles.slice();
    let cursor = 0;
    let lastFocus = null;

    const paintLightbox = () => {
      const tile = visible[cursor];
      if (!tile || !lightboxImg) return;
      const img = tile.querySelector('img');
      lightboxImg.src = tile.getAttribute('data-full') || (img ? img.src : '');
      lightboxImg.alt = img ? img.alt : '';
      if (lightboxCat) lightboxCat.textContent = tile.getAttribute('data-cat-label') || '';
      if (lightboxCaption) lightboxCaption.textContent = tile.getAttribute('data-caption') || '';
      if (prevBtn) prevBtn.disabled = visible.length < 2;
      if (nextBtn) nextBtn.disabled = visible.length < 2;
    };

    const openLightbox = (tile) => {
      if (!lightbox) return;
      cursor = Math.max(0, visible.indexOf(tile));
      paintLightbox();
      lastFocus = document.activeElement;
      lightbox.hidden = false;
      window.requestAnimationFrame(() => lightbox.classList.add('is-open'));
      document.body.style.overflow = 'hidden';
      const closeBtn = lightbox.querySelector('[data-lightbox-close]');
      if (closeBtn) closeBtn.focus();
    };

    const closeLightbox = () => {
      if (!lightbox) return;
      lightbox.classList.remove('is-open');
      document.body.style.overflow = '';
      window.setTimeout(() => {
        lightbox.hidden = true;
      }, 220);
      if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    };

    const stepLightbox = (dir) => {
      if (!visible.length) return;
      cursor = (cursor + dir + visible.length) % visible.length;
      paintLightbox();
    };

    tiles.forEach((tile) => {
      tile.addEventListener('click', () => openLightbox(tile));
    });

    filters.forEach((btn) => {
      btn.addEventListener('click', () => {
        const cat = btn.getAttribute('data-gallery-filter');
        filters.forEach((f) => {
          const active = f === btn;
          f.classList.toggle('is-active', active);
          f.setAttribute('aria-pressed', String(active));
        });
        tiles.forEach((tile) => {
          const show = cat === 'all' || tile.getAttribute('data-cat') === cat;
          tile.hidden = !show;
        });
        visible = tiles.filter((tile) => !tile.hidden);
        cursor = 0;
      });
    });

    if (lightbox) {
      lightbox.querySelectorAll('[data-lightbox-close]').forEach((btn) => btn.addEventListener('click', closeLightbox));
      if (prevBtn) prevBtn.addEventListener('click', () => stepLightbox(-1));
      if (nextBtn) nextBtn.addEventListener('click', () => stepLightbox(1));
      lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
      });
      document.addEventListener('keydown', (e) => {
        if (lightbox.hidden) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') stepLightbox(-1);
        if (e.key === 'ArrowRight') stepLightbox(1);
      });
    }
  }

  /* 7c. Board member modal */
  const memberModal = document.getElementById('atr-member-modal');
  if (memberModal) {
    const nameEl = memberModal.querySelector('[data-member-name]');
    const roleEl = memberModal.querySelector('[data-member-role]');
    const bioEl = memberModal.querySelector('[data-member-bio]');
    const mailEl = memberModal.querySelector('[data-member-mail]');
    const photoEl = memberModal.querySelector('[data-member-photo]');
    let memberFocus = null;

    const closeMember = () => {
      memberModal.classList.remove('is-open');
      document.body.style.overflow = '';
      window.setTimeout(() => {
        memberModal.hidden = true;
      }, 220);
      if (memberFocus && typeof memberFocus.focus === 'function') memberFocus.focus();
    };

    document.querySelectorAll('[data-member]').forEach((card) => {
      card.addEventListener('click', () => {
        const bio = card.querySelector('.atr-member__bio');
        const email = card.getAttribute('data-email') || '';
        if (nameEl) nameEl.textContent = card.getAttribute('data-name') || '';
        if (roleEl) roleEl.textContent = card.getAttribute('data-role') || '';
        if (bioEl) bioEl.textContent = bio ? bio.textContent : '';
        if (mailEl) mailEl.href = 'mailto:' + email;
        if (photoEl) {
          const photo = card.getAttribute('data-photo') || '';
          if (photo) {
            photoEl.src = photo;
            photoEl.alt = 'Placeholder portrait for ' + (card.getAttribute('data-name') || 'director');
            photoEl.hidden = false;
          } else {
            photoEl.hidden = true;
            photoEl.removeAttribute('src');
          }
        }
        memberFocus = card;
        memberModal.hidden = false;
        window.requestAnimationFrame(() => memberModal.classList.add('is-open'));
        document.body.style.overflow = 'hidden';
        const closeBtn = memberModal.querySelector('[data-member-close]');
        if (closeBtn) closeBtn.focus();
      });
    });

    memberModal.querySelectorAll('[data-member-close]').forEach((btn) => btn.addEventListener('click', closeMember));
    memberModal.addEventListener('click', (e) => {
      if (e.target === memberModal) closeMember();
    });
    document.addEventListener('keydown', (e) => {
      if (memberModal.hidden) return;
      if (e.key === 'Escape') closeMember();
    });
  }

  /* 7d. FAQ accordion */
  const faqToggles = Array.from(document.querySelectorAll('[data-faq-toggle]'));
  faqToggles.forEach((btn) => {
    btn.addEventListener('click', () => {
      const open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!open));
      const panel = document.getElementById(btn.getAttribute('aria-controls') || '');
      if (panel) panel.hidden = open;
      if (!open) {
        faqToggles.forEach((other) => {
          if (other !== btn) {
            other.setAttribute('aria-expanded', 'false');
            const otherPanel = document.getElementById(other.getAttribute('aria-controls') || '');
            if (otherPanel) otherPanel.hidden = true;
          }
        });
      }
    });
  });


  /* 8. Floating Scroll-to-Top Control with Reading Progress */
  const scrollTopBtn = document.getElementById('scroll-top');
  if (scrollTopBtn) {
    const SHOW_AFTER = 420;
    let queued = false;

    const paintScrollTop = () => {
      queued = false;
      const scrolled = window.scrollY || window.pageYOffset || 0;
      const doc = document.documentElement;
      const max = Math.max(1, doc.scrollHeight - window.innerHeight);
      const ratio = Math.min(1, Math.max(0, scrolled / max));

      if (scrolled > SHOW_AFTER) {
        if (scrollTopBtn.hidden) scrollTopBtn.hidden = false;
        window.requestAnimationFrame(() => scrollTopBtn.classList.add('is-visible'));
      } else {
        scrollTopBtn.classList.remove('is-visible');
        if (!scrollTopBtn.hidden) {
          window.setTimeout(() => {
            if (!scrollTopBtn.classList.contains('is-visible')) scrollTopBtn.hidden = true;
          }, 240);
        }
      }

      // Drives the conic-gradient ring around the button face.
      scrollTopBtn.style.setProperty('--scroll-progress', ratio.toFixed(4));
      scrollTopBtn.setAttribute('aria-label', `Scroll back to the top of the page (${Math.round(ratio * 100)}% read)`);
    };

    const onScroll = () => {
      if (queued) return;
      queued = true;
      window.requestAnimationFrame(paintScrollTop);
    };

    scrollTopBtn.addEventListener('click', () => {
      const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
      scrollTopBtn.blur();
    });

    // Touch browsers can keep :active/:hover latched after a tap; drop the
    // visual state explicitly so the control never stays in a pressed colour.
    ['touchend', 'touchcancel'].forEach((evt) => {
      scrollTopBtn.addEventListener(evt, () => scrollTopBtn.blur(), { passive: true });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    paintScrollTop();
  }

})();
