$file = "index.html"
if (!(Test-Path $file)) { Write-Error "Errore: non trovo index.html"; exit 1 }
Copy-Item $file "$file.bak" -Force
$content = Get-Content $file -Raw -Encoding UTF8
if ($content -notmatch "pricing-override.css") {
  $content = $content -replace "</head>", "  <link rel=`"stylesheet`" href=`"pricing-override.css`">`n</head>"
}
if ($content -notmatch "attach-narrow-pricing.js") {
  $content = $content -replace "</body>", "  <script defer src=`"attach-narrow-pricing.js`"></script>`n</body>"
}
Set-Content $file $content -Encoding UTF8
Write-Host "Fatto! Backup: index.html.bak"
