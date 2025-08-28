# Pacchetto: caselle prezzi più strette (360px)

Questa variante rende le card dei piani **ancora più strette**: max-width impostata a **360px** (default precedente 420px),
con padding e spazi ridotti.

## Contenuto
- `pricing-override.css` — CSS con valori già settati a 360px.
- `attach-narrow-pricing.js` — aggiunge classi alle card e al contenitore.
- `install_mac_linux.sh` — inserisce i riferimenti a CSS/JS in `index.html` (backup automatico).
- `install_windows.ps1` — stessa cosa per Windows.
- `README.md` — istruzioni.

## Uso rapido
1) Carica i file nel repo (stessa cartella di `index.html`).  
2) In `index.html`, aggiungi:
   ```html
   <link rel="stylesheet" href="pricing-override.css"><!-- prima di </head> -->
   ...
   <script defer src="attach-narrow-pricing.js"></script><!-- prima di </body> -->
   ```
   *(Oppure lancia gli script inclusi per farlo in automatico.)*

## Ripristino
C'è `index.html.bak` creato dagli script. In alternativa usa il commit precedente.
