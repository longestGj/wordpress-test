// PROTOTYPE_ONLY / LOCAL_SIMULATION. Production initial HTML must have resolved visible/graph parity.
(()=>{if(new URLSearchParams(location.search).get('pt')==='unavailable')document.querySelector('a[href="/pt-br/markets/brazil/"]')?.remove();
const graph=document.querySelector('#market-destination-graph'),data=JSON.parse(graph.textContent);
const visible=new Set([...document.querySelectorAll('[data-ready-action="market"]')].map(a=>a.getAttribute('href')));
data.itemListElement=data.itemListElement.filter(x=>visible.has(new URL(x.url).pathname)).map((x,i)=>({...x,position:i+1}));
if(data.itemListElement.length)graph.textContent=JSON.stringify(data);else graph.remove();})();
