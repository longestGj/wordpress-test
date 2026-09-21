// LOCAL_SIMULATION only. Query modes never assert actual D32 target readiness.
(()=>{const state=new URLSearchParams(location.search).get('state')||'full';
for(const node of document.querySelectorAll('[data-ready-action]')){
 const href=node.getAttribute('href'),kind=node.dataset.readyAction;
 const ready=state==='full'||(state==='eu-only'&&href==='/markets/european-union/')||(state==='support-only'&&kind==='support');
 if(!ready){if(node.closest('#eu-countries')){const span=document.createElement('span');span.className='plainCountry';span.textContent=node.textContent;node.replaceWith(span);}else node.remove();}
}
const button=document.querySelector('#eu-toggle'),countries=document.querySelector('#eu-countries'),mq=matchMedia('(max-width:767px)');
function set(open){countries.hidden=!open;button.setAttribute('aria-expanded',String(open));button.textContent=open?'Hide EU country destinations':'View EU country destinations';}
function size(){button.hidden=!mq.matches;set(!mq.matches)}button.addEventListener('click',()=>set(button.getAttribute('aria-expanded')!=='true'));mq.addEventListener('change',size);size();
})();
