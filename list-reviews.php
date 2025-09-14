<?php
/**
 * list-reviews.php
 * Restituisce in JSON le recensioni più recenti salvate in data/reviews.jsonl (JSON Lines).
 *
 * Parametri:
 *   ?limit=20   numero massimo di recensioni (default 20, max 200)
 */
header('Content-Type: application/json; charset=utf-8');
// header('Access-Control-Allow-Origin: https://TUO_DOMINIO.it'); // opzionale se serve CORS

$DATA_FILE = __DIR__ . '/data/reviews.jsonl';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
if ($limit < 1) $limit = 20;
if ($limit > 200) $limit = 200;

$items = [];
if (is_file($DATA_FILE)) {
  $lines = file($DATA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $lines = array_reverse($lines); // più recenti prima
  foreach ($lines as $i => $line) {
    if ($i >= $limit) break;
    $row = json_decode($line, true);
    if (!$row) continue;
    if (isset($row['approved']) && !$row['approved']) continue;
    $items[] = $row;
  }
}

echo json_encode(['ok'=>true, 'reviews'=>$items], JSON_UNESCAPED_UNICODE);
