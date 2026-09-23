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
