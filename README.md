# Aggiornamento prezzo "Starter" in grassetto + descrizione

Questo pacchetto aggiorna la sezione "Starter" nel tuo sito GitHub Pages.

## Modifiche automatiche
- Prezzo mostrato come: `€ <strong>450</strong> una tantum`
- Testo descrizione modificato da: `Landing 1–2 sezioni` → `Landing 1 sezione`

---

## Opzione A — Modifica manuale su GitHub
1. Apri `index.html` su GitHub.
2. Trova la sezione del piano **Starter**.
3. Sostituisci la riga del prezzo con:
   ```html
   € <strong>450</strong> una tantum
   ```
4. Sostituisci anche `Landing 1–2 sezioni` con `Landing 1 sezione`.
5. Fai **Commit**.

---

## Opzione B — Usare gli script (locale)
- macOS/Linux: `sed_update.sh`
- Windows PowerShell: `powershell_update.ps1`

Gli script faranno automaticamente:
- Sostituzione descrizione
- Aggiornamento prezzo Starter in formato con 450 in grassetto

Backup: `index.html.bak`

---
