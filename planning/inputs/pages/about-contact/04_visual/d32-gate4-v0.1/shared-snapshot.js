(()=>{
  const menu=document.querySelector('.mobileNav');
  const toggle=document.querySelector('.menuButton');
  const main=document.querySelector('main');
  const footer=document.querySelector('footer');
  if(!menu||!toggle||!main||!footer)throw new Error('Shared Chrome slots are incomplete');
  const background=[main,footer,document.querySelector('.logoLink'),document.querySelector('.headerRfq')].filter(Boolean);
  function setMenu(open,{returnFocus=true}={}){
    document.body.style.overflow=open?'hidden':'';
    menu.hidden=!open;
    toggle.textContent=open?'Close':'Menu';
    toggle.setAttribute('aria-expanded',String(open));
    background.forEach(node=>{node.inert=open});
    if(open)menu.querySelector('a')?.focus();
    else if(returnFocus)toggle.focus({preventScroll:true});
  }
  toggle.addEventListener('click',()=>setMenu(menu.hidden));
  menu.querySelectorAll('a').forEach(link=>link.addEventListener('click',()=>setMenu(false)));
  document.addEventListener('keydown',event=>{
    if(menu.hidden)return;
    if(event.key==='Escape'){event.preventDefault();setMenu(false);return}
    if(event.key==='Tab'){
      const nodes=[toggle,...menu.querySelectorAll('a')],first=nodes[0],last=nodes.at(-1);
      if(event.shiftKey&&document.activeElement===first){event.preventDefault();last.focus()}
      else if(!event.shiftKey&&document.activeElement===last){event.preventDefault();first.focus()}
    }
  });
  window.matchMedia('(min-width:1101px)').addEventListener('change',event=>{if(event.matches&&!menu.hidden)setMenu(false)});
  const cookie=document.querySelector('.cookie-layer');
  const cookieTrigger=document.querySelector('#cookie-trigger');
  if(!cookie||!cookieTrigger)throw new Error('Cookie settings slots are incomplete');
  const close=cookie.querySelector('[data-cookie-close]');
  const policy=cookie.querySelector('a');
  cookieTrigger.addEventListener('click',()=>{cookie.showModal();close.focus()});
  close.addEventListener('click',()=>cookie.close());
  cookie.addEventListener('close',()=>cookieTrigger.focus({preventScroll:true}));
  cookie.addEventListener('keydown',event=>{
    if(event.key==='Tab'&&event.shiftKey&&document.activeElement===close){event.preventDefault();policy.focus()}
    else if(event.key==='Tab'&&!event.shiftKey&&document.activeElement===policy){event.preventDefault();close.focus()}
  });
})();
