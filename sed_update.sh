#!/usr/bin/env bash
set -euo pipefail

PRICE=$(jq -r '.starter_price' config.json)
FILE="index.html"

if [[ ! -f "$FILE" ]]; then
  echo "Errore: non trovo $FILE nella cartella corrente."
  exit 1
fi

# Backup
cp "$FILE" "$FILE.bak"

# Descrizione
sed -i '' -e 's/Landing 1–2 sezioni/Landing 1 sezione/g' "$FILE"
sed -i '' -e 's/Landing 1-2 sezioni/Landing 1 sezione/g' "$FILE"

# Prezzo
awk -v newprice="$PRICE" '
  BEGIN { inStarter=0; replaced=0 }
  /###[[:space:]]*Starter/ { inStarter=1; print; next }
  {
    if (inStarter==1 && replaced==0) {
      if ($0 ~ /€.*una tantum/) {
        print newprice
        replaced=1
        inStarter=0
        next
      }
    }
    print
  }
' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"

echo "Aggiornato con prezzo in grassetto e descrizione corretta. Backup: index.html.bak"
