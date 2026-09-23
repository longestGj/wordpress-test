(() => {
  const page = document.querySelector('.document-guide');
  if (!page) return;
  const grade = page.querySelector('#document-grade');
  const detail = page.querySelector('#document-grade-detail');
  const choices = [...page.querySelectorAll('input[name="document-types"]')];
  const actions = [...page.querySelectorAll('[data-document-request]')];
  const bases = new Map(actions.map(a => [a, a.href]));
  const sync = () => {
    if (detail && grade) {
      const option = grade.selectedOptions[0];
      detail.hidden = !option?.dataset.url;
      if (option?.dataset.url) { detail.href = option.dataset.url; detail.textContent = `View ${option.value} Grade`; }
      else detail.removeAttribute('href');
    }
    actions.forEach(a => {
      const url = new URL(bases.get(a));
      choices.filter(c => c.checked).forEach(c => url.searchParams.append('prefill.document_types[]', c.value));
      if (grade?.value) url.searchParams.set('prefill.product_grade', grade.value);
      a.href = url.href;
    });
  };
  grade?.addEventListener('change', sync);
  choices.forEach(c => c.addEventListener('change', sync));
  sync();
})();
