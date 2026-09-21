document.addEventListener('click', event => {
  const button = event.target.closest('.tio2-add,.tio2-remove,.tio2-up');
  if (!button) return;
  const group = button.closest('.tio2-repeater');
  const items = group.querySelector(':scope > .tio2-items');
  if (button.classList.contains('tio2-up')) {
    const item = button.closest('.tio2-item');
    if(item.previousElementSibling)items.insertBefore(item,item.previousElementSibling);
  } else if (button.classList.contains('tio2-remove')) {
    if (items.children.length === 1) return; // Keep one editable row; disable it if it must not render.
    button.closest('.tio2-item').remove();
  } else {
    const clone = items.lastElementChild.cloneNode(true);
    clone.querySelectorAll('textarea').forEach(field => field.value = '');
    clone.querySelectorAll('input[type=checkbox]').forEach(field => field.checked = true);
    items.append(clone);
  }
  [...items.children].forEach((item, index) => {
    item.querySelectorAll('[name]').forEach(field => {
      const prefix = group.dataset.prefix + '[';
      if (field.name.startsWith(prefix)) field.name = prefix + index + field.name.slice(field.name.indexOf(']', prefix.length));
      const previousId = field.id;
      field.id = 'tio2-' + field.name.replace(/[^a-zA-Z0-9_-]/g, '-');
      item.querySelectorAll('label').forEach(label => { if(label.htmlFor === previousId)label.htmlFor = field.id; });
    });
    item.querySelectorAll('[data-prefix]').forEach(nested => {
      const prefix = group.dataset.prefix + '[';
      if(nested.dataset.prefix.startsWith(prefix))nested.dataset.prefix = prefix + index + nested.dataset.prefix.slice(nested.dataset.prefix.indexOf(']', prefix.length));
    });
  });
});
