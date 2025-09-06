
# WebLab Logo Pack

Contenuto:
- `assets/logo/logo-weblab-200.png` → consigliato per l'header (retina: `logo-weblab-400.png`).
- `assets/logo/logo-weblab-64.png`  → icone piccole o placeholder.
- `assets/logo/favicon.ico`         → icona del sito.

## Come inserirlo nella tua landing page (GitHub Pages)

1. **Copia la cartella** `assets/` dentro la root del tuo repository (stesso livello di `index.html`).
2. **Aggiungi il logo nell'HTML**, ad esempio nel tuo header/nav:

   ```html
   <header class="site-header">
     <img src="assets/logo/logo-weblab-200.png" alt="&amp; WebLab logo" class="site-logo" />
   </header>
   ```

3. **Aggiungi il link alla favicon** dentro `<head>`:

   ```html
   <link rel="icon" href="assets/logo/favicon.ico" sizes="any">
   ```

4. **Stili consigliati** (puoi incollare in un tuo file CSS o usare `logo.css` incluso):

   ```css
   .site-header {
     display: flex;
     align-items: center;
     gap: 12px;
     padding: 12px 16px;
   }
   .site-logo {
     height: 48px; /* regola l'altezza per l'header */
     width: auto;
     image-rendering: -webkit-optimize-contrast;
   }
   @media (min-resolution: 2dppx) {
     /* usa l'immagine 2x su schermi retina */
     .site-logo { content: url("assets/logo/logo-weblab-400.png"); }
   }
   ```

5. **Commit & Push** su GitHub:
   - `git add assets index.html` (o i file che hai modificato)
   - `git commit -m "Add optimized WebLab logo"`
   - `git push`

6. Verifica su `https://giuppe5401-stack.github.io/` che il logo compaia correttamente.
   Se non lo vedi, svuota la cache o ricarica forzando (CTRL/CMD + Shift + R).

## Note
- Tutte le immagini hanno sfondo trasparente e sono pronte per temi chiari/scuri.
- Se vuoi un'altezza diversa del logo, modifica `.site-logo { height: ... }`.
- Per massima nitidezza, usa `logo-weblab-200.png` e lascia al CSS solo l'altezza.
