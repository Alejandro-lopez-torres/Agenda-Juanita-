// Utilidades básicas
function qs(sel, el=document){ return el.querySelector(sel); }
function qsa(sel, el=document){ return Array.from(el.querySelectorAll(sel)); }

// Debounce
function debounce(fn, ms){ let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); }; }

// Autocomplete de clientes
(function(){
  const input = qs('#cliente_search');
  const hiddenId = qs('#client_id');
  const box = qs('#cliente_results');
  if(!input || !box) return;

  const render = (items) => {
    if (!items.length) { box.innerHTML = ''; return; }
    const list = document.createElement('div');
    list.className = 'list';
    items.forEach(it=>{
      const div = document.createElement('div');
      div.className = 'item';
      div.textContent = `${it.nombre} ${it.apellido} | DNI: ${it.dni} | ${it.correo}`;
      div.addEventListener('click', () => {
        hiddenId.value = it.id;
        input.value = `${it.nombre} ${it.apellido} (${it.dni})`;
        box.innerHTML = '';
      });
      list.appendChild(div);
    });
    box.innerHTML = '';
    box.appendChild(list);
  };

  const search = debounce(async (q)=>{
    if ((q||'').length < 2) { box.innerHTML = ''; return; }
    const url = `?c=clientes&a=search&q=${encodeURIComponent(q)}`;
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    render(data.items || []);
  }, 250);

  input.addEventListener('input', (e)=>{
    hiddenId.value = '';
    search(e.target.value);
  });
})();
