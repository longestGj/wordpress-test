const menu = document.querySelector('#site-menu');
const trigger = document.querySelector('.menuButton');
if (menu && trigger) {
  trigger.addEventListener('click', () => { menu.showModal(); trigger.setAttribute('aria-expanded', 'true'); });
  menu.querySelector('.menuClose').addEventListener('click', () => menu.close());
  menu.addEventListener('close', () => { trigger.setAttribute('aria-expanded', 'false'); trigger.focus(); });
  menu.addEventListener('keydown', event => {
    if(event.key !== 'Tab') return;
    const focusable = [...menu.querySelectorAll('button,a[href]')];
    const first = focusable[0], last = focusable.at(-1);
    if(event.shiftKey && document.activeElement === first){event.preventDefault();last.focus();}
    else if(!event.shiftKey && document.activeElement === last){event.preventDefault();first.focus();}
  });
}
