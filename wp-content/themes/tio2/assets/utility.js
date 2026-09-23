const cookieDialog = document.querySelector('#cookie-settings');
if (location.hash === '#general-inquiry') document.querySelector('.utility-notice')?.focus();
if (cookieDialog) {
  const close = cookieDialog.querySelector('.cookie-settings-close');
  let opener;
  document.querySelectorAll('.cookie-settings-open').forEach(button => button.addEventListener('click', () => {
    opener = button;
    cookieDialog.showModal();
    close.focus();
  }));
  close.addEventListener('click', () => cookieDialog.close());
  cookieDialog.addEventListener('close', () => opener?.focus());
  cookieDialog.addEventListener('click', event => { if (event.target === cookieDialog) cookieDialog.close(); });
}

const consentPanel = document.querySelector('#analytics-consent');
const measurementId = consentPanel?.dataset.ga4Id;
if (consentPanel && /^G-[A-Z0-9]{7,}$/.test(measurementId || '')) {
  const storageKey = 'tio2_analytics_consent_v1';
  const isMalay = document.documentElement.lang.toLowerCase().startsWith('ms');
  const status = document.querySelector('#analytics-status');
  let loaded = false;

  function storedChoice() {
    try { return localStorage.getItem(storageKey); } catch { return null; }
  }

  function rememberChoice(choice) {
    try { localStorage.setItem(storageKey, choice); } catch { /* An explicit choice still applies to this page. */ }
  }

  function updateStatus(choice) {
    if (!status) return;
    status.textContent = isMalay
      ? (choice === 'granted' ? 'Analitik dihidupkan.' : choice === 'denied' ? 'Analitik dimatikan.' : 'Analitik belum dipilih.')
      : (choice === 'granted' ? 'Analytics is enabled.' : choice === 'denied' ? 'Analytics is disabled.' : 'No analytics choice has been saved.');
  }

  function safeReferrer() {
    if (!document.referrer) return '';
    try {
      const url = new URL(document.referrer);
      return url.origin + url.pathname;
    } catch { return ''; }
  }

  function loadAnalytics() {
    if (loaded) return;
    loaded = true;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('consent', 'default', {
      analytics_storage: 'denied', ad_storage: 'denied',
      ad_user_data: 'denied', ad_personalization: 'denied'
    });
    window.gtag('consent', 'update', {
      analytics_storage: 'granted', ad_storage: 'denied',
      ad_user_data: 'denied', ad_personalization: 'denied'
    });
    window.gtag('js', new Date());
    window.gtag('config', measurementId, {
      page_location: location.origin + location.pathname,
      page_referrer: safeReferrer(),
      allow_google_signals: false,
      allow_ad_personalization_signals: false
    });
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId);
    document.head.append(script);
  }

  function removeAnalyticsCookies() {
    const names = document.cookie.split(';').map(item => item.trim().split('=')[0]);
    for (const name of names) {
      if (!/^_ga(?:_|$)|^_gid$|^_gat/.test(name)) continue;
      for (const domain of ['', '; Domain=' + location.hostname, '; Domain=.' + location.hostname]) {
        document.cookie = name + '=; Max-Age=0; Path=/' + domain + '; SameSite=Lax';
      }
    }
  }

  function choose(choice) {
    rememberChoice(choice);
    consentPanel.hidden = true;
    updateStatus(choice);
    if (cookieDialog?.open) cookieDialog.close();
    if (choice === 'granted') {
      loadAnalytics();
    } else {
      if (loaded) window.gtag('consent', 'update', {
        analytics_storage: 'denied', ad_storage: 'denied',
        ad_user_data: 'denied', ad_personalization: 'denied'
      });
      removeAnalyticsCookies();
      if (loaded) location.reload();
    }
  }

  consentPanel.querySelector('.analytics-allow')?.addEventListener('click', () => choose('granted'));
  consentPanel.querySelector('.analytics-reject')?.addEventListener('click', () => choose('denied'));
  cookieDialog?.querySelector('.analytics-enable')?.addEventListener('click', () => choose('granted'));
  cookieDialog?.querySelector('.analytics-disable')?.addEventListener('click', () => choose('denied'));

  const choice = storedChoice();
  updateStatus(choice);
  if (choice === 'granted') loadAnalytics();
  else if (choice === 'denied') removeAnalyticsCookies();
  else consentPanel.hidden = false;
}
