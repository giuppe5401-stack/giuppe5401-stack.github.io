#!/usr/bin/env bash
set -euo pipefail
FILE="index.html"
if [[ ! -f "$FILE" ]]; then echo "Errore: non trovo $FILE"; exit 1; fi
cp "$FILE" "$FILE.bak"
grep -q 'pricing-override.css' "$FILE" || sed -i '' -e $'s@</head>@  <link rel="stylesheet" href="pricing-override.css">\n</head>@' "$FILE"
grep -q 'attach-narrow-pricing.js' "$FILE" || sed -i '' -e $'s@</body>@  <script defer src="attach-narrow-pricing.js"></script>\n</body>@' "$FILE"
echo "Ok! Inseriti CSS/JS. Backup: index.html.bak"
