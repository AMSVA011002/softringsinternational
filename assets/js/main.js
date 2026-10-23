/**
 * Softrings International (Pvt) Ltd — Interactive Corporate Client Script
 * Uses relative endpoints (api.php) for seamless proxy / production compatibility.
 */

(function () {
  'use strict';

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

  /* 2. Live Sri Lanka Standard Time (Asia/Colombo) Clock */
  const slstClock = document.getElementById('slst-clock');
  if (slstClock) {
    const updateColomboClock = () => {
      try {
        const formatted = new Intl.DateTimeFormat('en-US', {
          timeZone: 'Asia/Colombo',
          hour: '2-digit',
          minute: '2-digit',
          hour12: true,
        }).format(new Date());
        slstClock.textContent = `Colombo ${formatted} SLST`;
      } catch (_) {
        // Fallback keeps server-rendered time
      }
    };
    updateColomboClock();
    setInterval(updateColomboClock, 30000);
  }

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

  /* 4. Reference Status Tracker Modal */
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

  const escapeHtml = (str) => {
    const div = document.createElement('div');
    div.textContent = String(str ?? '');
    return div.innerHTML;
  };

  if (trackerForm && trackerInput && trackerResult) {
    trackerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const ref = trackerInput.value.trim();
      if (!ref) return;
      trackerResult.innerHTML = '<div class="alert">Checking Softrings Corporate Desk ledger...</div>';
      try {
        const res = await fetch(`api.php?action=ticket&ref=${encodeURIComponent(ref)}`);
        const data = await res.json();
        if (!res.ok || !data.ok) {
          trackerResult.innerHTML = `<div class="alert alert--error"><p>${escapeHtml(data.error || 'Reference not found.')}</p></div>`;
          return;
        }
        const rec = data.record;
        trackerResult.innerHTML = `
          <div class="alert alert--success">
            <strong>${escapeHtml(rec.record_kind || 'Record')}: ${escapeHtml(rec.ref_code)}</strong>
            <p><strong>Applicant / Contact:</strong> ${escapeHtml(rec.full_name)}</p>
            <p><strong>Subject:</strong> ${escapeHtml(rec.subject || rec.inquiry_type || rec.job_title || '')}</p>
            <p><strong>Status:</strong> ${escapeHtml(rec.status)}</p>
            <p><strong>Logged:</strong> ${escapeHtml(rec.created_at)}</p>
          </div>
        `;
      } catch (_) {
        trackerResult.innerHTML = '<div class="alert alert--error"><p>Unable to reach verification endpoint. Please try again.</p></div>';
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

      // Indicative retail partner calculation (30 days * 4.5% blended reload/utility fee + Rs. 350/activation + Rs. 2,800 avg handset/modem margin)
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

  /* 7. Career Role Quick-Select & Progressive AJAX Forms */
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

  const bindAjaxForm = (formId, feedbackId, endpoint, transformPayload) => {
    const form = document.getElementById(formId);
    const feedback = document.getElementById(feedbackId);
    if (!form || !feedback) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.textContent : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting to Softrings Server...';
      }

      const fd = new FormData(form);
      const payload = transformPayload ? transformPayload(fd) : Object.fromEntries(fd.entries());

      try {
        const res = await fetch(endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok || !data.ok) {
          feedback.innerHTML = `<div class="alert alert--error" role="alert"><p>${escapeHtml(data.error || 'Submission could not be completed.')}</p></div>`;
        } else {
          feedback.innerHTML = `
            <div class="alert alert--success" role="status">
              <strong>Reference Code: ${escapeHtml(data.ref_code)}</strong>
              <p>${escapeHtml(data.message)}</p>
              <p><small>${escapeHtml(data.status)} · ${escapeHtml(data.created_at)}</small></p>
              <p style="margin-top:8px;">
                <button type="button" class="btn btn--secondary btn--sm" data-verify-now="${escapeHtml(data.ref_code)}">
                  Verify Reference in Live Tracker
                </button>
              </p>
            </div>
          `;
          const verifyBtn = feedback.querySelector('[data-verify-now]');
          if (verifyBtn) {
            verifyBtn.addEventListener('click', () => {
              openTracker(verifyBtn.getAttribute('data-verify-now') || '');
            });
          }
          form.reset();
        }
      } catch (_) {
        feedback.innerHTML = '<div class="alert alert--error"><p>Network error while submitting. Please try again.</p></div>';
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      }
    });
  };

  bindAjaxForm('contact-form', 'contact-ajax-feedback', 'api.php?action=contact');

  bindAjaxForm('career-form', 'career-ajax-feedback', 'api.php?action=apply', (fd) => {
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
})();
