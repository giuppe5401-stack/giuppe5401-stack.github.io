README — Sezione Recensioni (PHP)

Contenuto dello ZIP
-------------------
1) save-review.php
   - Riceve il POST dal form e salva le recensioni in data/reviews.jsonl (formato JSON Lines).
   - Anti-spam (honeypot), rate limit (120s), validazione server.
   - Redirect alla pagina del form con #lascia-recensione.
   - Se chiamato in AJAX con Accept: application/json risponde in JSON.

2) list-reviews.php
   - Restituisce le ultime recensioni in JSON leggendo data/reviews.jsonl.
   - Uso: /list-reviews.php?limit=12

3) data/   (cartella vuota, deve essere scrivibile dal server)
   - Verrà popolata con reviews.jsonl e file di rate-limit.

Istruzioni
----------
A) Caricamento file
   - Carica TUTTO lo ZIP nella cartella del tuo sito.
   - Verifica che esista la cartella 'data/' con permessi 755 o 775.

B) Modifica nella pagina HTML
   - Assicurati che il form abbia:
       <form id="review-form" class="card t-card" action="save-review.php" method="POST">
   - NON incollare il PHP dentro l'HTML: i file .php sono separati.

C) (Opzionale) Caricamento automatico card via JS
   - Aggiungi prima di </body>:
       <script>
       (function(){
         fetch('list-reviews.php?limit=12',{headers:{'Accept':'application/json'}})
           .then(r=>r.json())
           .then(d=>{
             if(!d.ok||!Array.isArray(d.reviews)) return;
             const WRAP = document.querySelector('#recensioni .cards.three');
             if(!WRAP) return;
             function stars(n){n=Math.max(1,Math.min(5,Number(n)||0));return '★★★★★'.slice(0,n)+'☆☆☆☆☆'.slice(0,5-n);}
             function initials(name){if(!name) return '';return name.trim().split(/\\s+/).map(s=>s[0]?.toUpperCase()).join('').slice(0,2);}
             d.reviews.forEach(r=>{
               const art=document.createElement('article');art.className='card t-card';art.setAttribute('data-auto','1');
               const p=document.createElement('p');p.className='t-quote';p.textContent='“'+(r.text||'')+'”';art.appendChild(p);
               const meta=document.createElement('div');meta.className='t-meta';
               const av=document.createElement('span');av.className='t-avatar';av.textContent=initials(r.name||'');av.setAttribute('aria-hidden','true');meta.appendChild(av);
               const person=document.createElement('div');person.className='t-person';
               const nm=document.createElement('span');nm.className='t-name';nm.textContent=r.name||'Cliente';person.appendChild(nm);
               const rl=document.createElement('span');rl.className='t-role';rl.textContent=r.role||'Cliente';person.appendChild(rl);
               meta.appendChild(person);
               const st=document.createElement('span');st.className='t-stars';st.setAttribute('aria-label','Valutazione '+(r.rating||5)+' su 5');st.textContent=stars(r.rating||5);meta.appendChild(st);
               art.appendChild(meta);
               WRAP.insertBefore(art, WRAP.firstChild);
             });
           })
           .catch(console.error);
       })();
       </script>

D) Test rapido
   - Apri la pagina, compila il form, invia.
   - Dovresti essere reindirizzato di nuovo alla pagina con #lascia-recensione.
   - Controlla su server: data/reviews.jsonl deve contenere una riga JSON per la recensione.
   - Apri /list-reviews.php?limit=5 per vedere il JSON delle ultime recensioni.

Requisiti
---------
- Hosting con PHP 7.4+ (consigliato 8.x).
- Permessi di scrittura sulla cartella data/.
- Niente database richiesto.
