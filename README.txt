README — Bundle recensioni con caricamento automatico

File inclusi
------------
1) save-review.php
2) list-reviews.php     (supporta ?limit e ?offset)
3) reviews-auto.js      (caricamento iniziale + bottone "Mostra altre")
4) recensioni-fragment.html
5) data/ (cartella vuota)

Istruzioni
----------
1) Carica tutti i file sul server (stessa cartella della tua pagina).
2) Assicurati che 'data/' sia scrivibile (permessi 755 o 775).
3) Nel tuo HTML:
   - Il form deve avere action=\"save-review.php\" method=\"POST\".
   - Sostituisci la tua sezione recensioni con 'recensioni-fragment.html'
     OPPURE assicurati che esista <div class=\"cards three\" role=\"list\"></div>
   - Prima di </body> inserisci:
       <script src=\"reviews-auto.js\" defer></script>

Test
----
- Invia una recensione dal form: si salva in data/reviews.jsonl e al refresh sarà visibile.
- Clicca \"Mostra altre\" per caricare ulteriori recensioni.
