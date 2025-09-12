
// & WebLab Landing - script.js
(function(){
  'use strict';
  // Accessibility: reduce motion preference
  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Smooth scroll for internal links
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', (e) => {
      const target = document.querySelector(a.getAttribute('href'));
      if(target){
        e.preventDefault();
        target.scrollIntoView({ behavior: prefersReduced ? 'auto' : 'smooth', block: 'start' });
      }
    });
  });

  // Form validation
  const form = document.getElementById('contact-form');
  if(form){
    form.addEventListener('submit', (e) => {
      const name = form.querySelector('[name="name"]');
      const email = form.querySelector('[name="email"]');
      const message = form.querySelector('[name="message"]');
      let ok = true;
      [name, email, message].forEach(field => {
        field.setCustomValidity('');
        if(!field.value.trim()) { field.setCustomValidity('Campo obbligatorio'); ok = false; }
      });
      const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if(email.value && !emailRe.test(email.value)) { email.setCustomValidity('Email non valida'); ok = false; }
      if(!ok){ e.preventDefault(); form.reportValidity(); }
    });
  }

  // Lightweight analytics stub
  window._weblab = window._weblab || { events: [] };
  document.querySelectorAll('[data-track]').forEach(el => {
    el.addEventListener('click', () => {
      window._weblab.events.push({ t: Date.now(), action: el.getAttribute('data-track') });
    });
  });
})();


  // Hamburger toggle
  const menuToggle = document.getElementById('menu-toggle');
  const nav = document.querySelector('nav.badges');
  if(menuToggle && nav){
    menuToggle.addEventListener('click', ()=>{
      nav.classList.toggle('active');
    });
  }


// Mobile menu toggle
(function(){
  const btn = document.getElementById('nav-toggle');
  const menu = document.getElementById('site-menu');
  if(btn && menu){
    btn.addEventListener('click', () => {
      const opened = menu.classList.toggle('open');
      btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
    });
  }
})();
