(() => {
  const page=document.querySelector('.request-page');if(!page)return;
  const form=page.querySelector('form.request-form');if(!form)return;
  const summary=page.querySelector('#request-errors');if(summary)summary.focus({preventScroll:true});
  const application=form.querySelector('#request-application');
  const other=form.querySelector('#request-application_other');
  if(application&&other){
    const container=other.closest('.request-field');
    const sync=()=>{container.hidden=application.value!=='Other';other.required=application.value==='Other';};
    application.addEventListener('change',()=>{sync();if(application.value==='Other')other.focus();});sync();
  }
  form.addEventListener('submit',()=>{
    const button=form.querySelector('[type="submit"]');
    if(button){button.disabled=true;button.textContent='SUBMITTING…';}
  });
})();
