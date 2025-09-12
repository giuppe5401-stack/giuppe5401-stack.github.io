// & WebLab v4.1 — JS
(function(){
  const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  // Skip link target
  const main = document.querySelector('main'); if(main){ main.setAttribute('tabindex','-1'); }
  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{
    const t = document.querySelector(a.getAttribute('href')); if(t){ e.preventDefault(); t.scrollIntoView({behavior: reduced?'auto':'smooth', block:'start'}); t.focus && t.focus({preventScroll:true}); } }));
  // Mobile menu
  const btn=document.getElementById('menu-toggle'); const menu=document.getElementById('site-menu');
  if(btn && menu){ btn.setAttribute('aria-expanded','false'); btn.addEventListener('click', ()=>{ const opened=menu.classList.toggle('open'); btn.setAttribute('aria-expanded', opened?'true':'false'); }); }
  // Form validation demo
  const form=document.getElementById('contact-form');
  if(form){ form.addEventListener('submit', (e)=>{
    const req = form.querySelectorAll('[required]'); let ok=true;
    req.forEach(f=>{ f.setCustomValidity(''); if(!f.value.trim()){ f.setCustomValidity('Campo obbligatorio'); ok=false; }});
    const email = form.querySelector('input[type="email"]');
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; if(email && email.value && !re.test(email.value)){ email.setCustomValidity('Email non valida'); ok=false; }
    if(!ok){ e.preventDefault(); form.reportValidity(); }
  }); }
})();