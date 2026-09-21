// Query states are diagnostic LOCAL_SIMULATION, never buyer UI or a fact decision.
// Default always retains the full currently approved public baseline.
(()=>{const state=new URLSearchParams(location.search).get('evidence');
 if(['partial','restricted'].includes(state))document.querySelectorAll('[data-scale]').forEach(e=>e.remove());
 if(state==='restricted'){document.querySelectorAll('.claim').forEach(e=>e.remove());document.querySelector('meta[name="description"]').remove();document.title='About TiO2 Malaysia';}
})();
