(() => {
  const hub = document.querySelector('main.hub');
  if (!hub) return;
  const buttons = [...hub.querySelectorAll('[data-app]')];
  const panels = [...hub.querySelectorAll('[data-result-app]')];
  const heading = hub.querySelector('#result-heading');
  buttons.forEach(button => button.addEventListener('click', () => {
    const app = button.dataset.app;
    buttons.forEach(item => {
      const active = item === button;
      item.setAttribute('aria-pressed', String(active));
      const check = item.querySelector('.check');
      if (check) check.textContent = active ? '✓ ' : '';
    });
    panels.forEach(panel => { panel.hidden = panel.dataset.resultApp !== app; });
    const panel = panels.find(panel => panel.dataset.resultApp === app);
    if (heading) heading.textContent = app === 'Not Sure' ? 'Not Sure' : `Grades to Review — ${panel.querySelectorAll('.result').length} for ${app}`;
  }));
  if (heading) heading.setAttribute('aria-live','polite');
  const select = hub.querySelector('#product-grade');
  if (select) {
    const error = hub.querySelector('#grade-error');
    const selected = hub.querySelector('#grade-selected');
    const actions = [...hub.querySelectorAll('[data-document-continue]')];
    const receiver = window.tio2DocumentReceiver || '';
    const valid = () => select.value && [...select.options].some(o => o.value === select.value);
    const sync = () => {
      error.textContent = '';select.removeAttribute('aria-invalid');
      selected.textContent = valid() ? `Selected product grade: ${select.value}` : '';
      actions.forEach(a => {a.href = receiver && valid() ? `${receiver}?product=${encodeURIComponent(select.value)}` : '#grade-selector';});
    };
    select.addEventListener('change', sync);
    actions.forEach(a => a.addEventListener('click', event => {
      if (!valid()) {event.preventDefault();error.textContent='Select a product grade to continue.';select.setAttribute('aria-invalid','true');select.focus();}
      else if (!receiver) {event.preventDefault();error.textContent='Document requests are not connected in this local preview.';}
    }));
    sync();
  }
})();
