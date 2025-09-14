<?php
/**
 * save-review.php
 * Salva recensioni localmente in formato JSON Lines (una recensione per riga).
 * - Anti-spam: honeypot + rate-limit per IP (120s)
 * - Validazione lato server
 * - Reindirizzo alla pagina di origine dopo l'invio (se Accept: text/html)
 * - Endpoint di lettura (GET ?format=json) per restituire le ultime recensioni in JSON
 *
 * ISTRUZIONI
 * 1) Carica questo file nella root del tuo sito (stessa cartella della pagina con il form).
 * 2) Crea la cartella ./data con permessi 755 o 775 (scrivibile dal server).
 * 3) Nel form HTML imposta: action="save-review.php" method="POST"
 * 4) (Opz.) Cambia $REDIRECT_ANCHOR se vuoi un'altra ancora di ritorno.
 */

// ==================== CONFIG ====================
$DATA_DIR         = __DIR__ . '/data';
$STORAGE_FILE     = $DATA_DIR . '/reviews.jsonl';   // JSON Lines
$RATE_LIMIT_SEC   = 120;                            // 1 invio ogni 120s per IP
$REDIRECT_ANCHOR  = '#lascia-recensione';          // ancora di ritorno dopo invio
$MAX_TEXT_CHARS   = 800;
$MIN_TEXT_CHARS   = 30;

// CORS opzionale (disabilitato di default). Imposta il tuo dominio se necessario.
// header('Access-Control-Allow-Origin: https://TUO_DOMINIO.it');

// Assicura esistenza cartella dati
if (!is_dir($DATA_DIR)) { @mkdir($DATA_DIR, 0755, true); }

// Utility: invio risposta JSON rispettando Accept header
function respond_json($payload, $status = 200) {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  exit;
}

// Utility: redirect indietro alla pagina chiamante
function redirect_back($anchor = '#') {
  $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
  // Evita open redirect: consenti solo stesso host
  $url = parse_url($ref);
  $host = $_SERVER['HTTP_HOST'] ?? '';
  if (!isset($url['host']) || $url['host'] !== $host) {
    $ref = '/';
  }
  // Aggiungi anchor se non c'è già
  if ($anchor && strpos($ref, $anchor) === false) {
    $ref .= $anchor;
  }
  header('Location: ' . $ref);
  exit;
}

// ====== GET: endpoint di lettura (opzionale) ======
// Esempio: /save-review.php?format=json&limit=20
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $format = isset($_GET['format']) ? strtolower($_GET['format']) : '';
  if ($format === 'json') {
    $limit = isset($_GET['limit']) ? max(1, min(200, (int)$_GET['limit'])) : 50;
    $items = [];
    if (is_file($STORAGE_FILE)) {
      // Leggi dal fondo per avere le più recenti
      $lines = file($STORAGE_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $lines = array_reverse($lines);
      foreach ($lines as $i => $line) {
        if ($i >= $limit) break;
        $row = json_decode($line, true);
        if (isset($row['approved']) && !$row['approved']) continue;
        if ($row) $items[] = $row;
      }
    }
    respond_json(['ok'=>true, 'reviews'=>$items]);
  } else {
    // Per GET senza format, mostra un semplice messaggio
    respond_json(['ok'=>true, 'message'=>'save-review endpoint online'], 200);
  }
}

// ====== POST: salvataggio recensione ======
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond_json(['ok'=>false,'error'=>'method_not_allowed'], 405);
}

// Estrai e valida campi
function get_field($key) { return isset($_POST[$key]) ? trim((string)$_POST[$key]) : ''; }

// Honeypot
if (get_field('company') !== '') {
  respond_json(['ok'=>false,'error'=>'spam_detected'], 400);
}

$name    = mb_substr(strip_tags(get_field('name')), 0, 60);
$role    = mb_substr(strip_tags(get_field('role')), 0, 60);
$rating  = (int) get_field('rating');
$text    = mb_substr(strip_tags(get_field('text')), 0, $MAX_TEXT_CHARS);
$consent = isset($_POST['consent']) && $_POST['consent'] !== '';

// Validazione
if ($name === '' || $rating < 1 || $rating > 5 || $text === '' || mb_strlen($text) < $MIN_TEXT_CHARS || !$consent) {
  respond_json(['ok'=>false,'error'=>'validation_failed'], 422);
}

// Rate limit per IP
$ip     = $_SERVER['REMOTE_ADDR']     ?? '';
$ua     = $_SERVER['HTTP_USER_AGENT'] ?? '';
$rlFile = $DATA_DIR . '/ratelimit_' . md5($ip) . '.txt';

$now = time();
if (is_file($rlFile)) {
  $last = (int) @file_get_contents($rlFile);
  if ($now - $last < $RATE_LIMIT_SEC) {
    $retry = $RATE_LIMIT_SEC - ($now - $last);
    respond_json(['ok'=>false,'error'=>'too_many_requests','retry_after'=>$retry], 429);
  }
}
@file_put_contents($rlFile, (string)$now);

$record = [
  'created_at' => date('c'),
  'name'       => $name,
  'role'       => $role !== '' ? $role : 'Cliente',
  'rating'     => $rating,
  'text'       => $text,
  'ip_hash'    => substr(hash('sha256', $ip . '|' . $ua), 0, 16),
  'approved'   => true // imposta a false se vuoi moderare manualmente
];

// Scrittura atomica JSONL
$fh = @fopen($STORAGE_FILE, 'a');
if (!$fh) {
  respond_json(['ok'=>false,'error'=>'storage_unavailable'], 500);
}
flock($fh, LOCK_EX);
fwrite($fh, json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL);
flock($fh, LOCK_UN);
fclose($fh);

// Se l'header Accept include text/html → redirect alla pagina del form
$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
if (stripos($accept, 'text/html') !== false) {
  redirect_back($REDIRECT_ANCHOR);
}

// Altrimenti rispondi JSON
respond_json(['ok'=>true]);
