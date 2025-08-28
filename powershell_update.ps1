# PowerShell script per aggiornare index.html (Windows)
$config = Get-Content "config.json" | ConvertFrom-Json
$price = $config.starter_price
$fromDesc = $config.starter_desc_from
$toDesc = $config.starter_desc_to

$file = "index.html"
if (!(Test-Path $file)) { Write-Error "Errore: non trovo $file"; exit 1 }

Copy-Item $file "$file.bak" -Force

# Descrizione
(Get-Content $file -Raw).
  Replace($fromDesc, $toDesc).
  Replace("Landing 1-2 sezioni", $toDesc) | Set-Content $file -Encoding UTF8

# Prezzo Starter
$lines = Get-Content $file -Raw -Encoding UTF8 -NewLine "`n" -ReadCount 0
$parts = $lines -split "`n"

$inStarter = $false
$replaced = $false
for ($i=0; $i -lt $parts.Length; $i++) {
  if ($parts[$i] -match "###\s*Starter") {
    $inStarter = $true
    continue
  }
  if ($inStarter -and -not $replaced) {
    if ($parts[$i] -match "€.*una tantum") {
      $parts[$i] = $price
      $replaced = $true
      $inStarter = $false
    }
  }
}

$updated = [string]::Join("`n", $parts)
Set-Content $file $updated -Encoding UTF8

Write-Host "Aggiornato con prezzo in grassetto e descrizione corretta. Backup: index.html.bak"
