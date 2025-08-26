GitHub Actions - Sitemap Auto Generation
============================================

Questo workflow genera e committa automaticamente `sitemap.xml` per GitHub Pages.

Come installarlo
----------------
1) Nel repo `giuppe5401-stack.github.io` crea (se non ci sono) le cartelle:
   `.github/workflows/`

2) Carica il file:
   `.github/workflows/generate-sitemap.yml`

3) Abilita Actions (se necessario):
   Settings → Actions → General → Allow all actions.

4) Forza la prima esecuzione:
   Tab "Actions" → workflow "Generate Sitemap" → "Run workflow".

Cosa fa
-------
- Esegue ad ogni push su `main`
- Può essere lanciato manualmente (workflow_dispatch)
- Esegue automaticamente ogni lunedì alle 03:00 UTC (cron)
- Scrive/aggiorna `sitemap.xml` nella root del repo

Dopo il primo run
-----------------
- Verifica che `https://giuppe5401-stack.github.io/sitemap.xml` sia accessibile.
- In Google Search Console → "Sitemap" → invia:
  https://giuppe5401-stack.github.io/sitemap.xml

Ultimo aggiornamento: 2025-08-26T23:40:57.275665Z
