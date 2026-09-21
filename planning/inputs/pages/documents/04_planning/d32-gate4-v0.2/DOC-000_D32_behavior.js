(()=>{
 const select=document.querySelector('#product-grade'),next=document.querySelector('#continue-request'),closing=document.querySelector('#closing-action'),error=document.querySelector('#grade-error'),confirmation=document.querySelector('#grade-selected');
 const grades=new Set(['M-350','M-510','M-896','M-996','M-2196','M-895','M-200','M-108','M-210','M-340','M-886','M-52','M-2377','CR-901']);
 function sync(){const valid=grades.has(select.value),href=valid?'/request-documents/?product='+encodeURIComponent(select.value):'#grade-selector';next.href=href;closing.href=href;closing.textContent=valid?'Continue to Request Documents':'Select a Product Grade';confirmation.textContent=valid?'Selected product grade: '+select.value:'';error.textContent='';select.removeAttribute('aria-invalid');}
 select.addEventListener('change',sync);
 next.addEventListener('click',e=>{if(!grades.has(select.value)){e.preventDefault();error.textContent='Select a product grade to continue.';select.setAttribute('aria-invalid','true');select.focus();}});
 closing.addEventListener('click',e=>{if(!grades.has(select.value)){e.preventDefault();select.focus();select.scrollIntoView({block:'center'});}});
 document.querySelectorAll('.faq-question').forEach(button=>button.addEventListener('click',()=>{const open=button.getAttribute('aria-expanded')!=='true';document.querySelectorAll('.faq-question').forEach(b=>{const expanded=b===button&&open;b.setAttribute('aria-expanded',String(expanded));b.querySelector('span').textContent=expanded?'−':'+';document.getElementById(b.getAttribute('aria-controls')).hidden=!expanded;});}));
 sync();
})();
