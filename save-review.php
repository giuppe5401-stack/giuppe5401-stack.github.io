<?php
// save-review.php — Salva recensioni in reviews.json (JSON array)
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'method_not_allowed']);
  exit;
}

function f($k){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }

// Honeypot antispam
if (f('company') !== '') {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'spam']);
  exit;
}

$name   = mb_substr(strip_tags(f('name')), 0, 60);
$role   = mb_substr(strip_tags(f('role')), 0, 60);
$rating = (int)f('rating');
$text   = mb_substr(strip_tags(f('text')), 0, 800);
$consent= isset($_POST['consent']);

if ($name === '' || $text === '' || mb_strlen($text) < 30 || $rating < 1 || $rating > 5 || !$consent) {
  http_response_code(422);
  echo json_encode(['ok'=>false,'error'=>'validation_failed']);
  exit;
}

$file = __DIR__ . '/reviews.json';
$data = [];
if (file_exists($file)) {
  $data = json_decode(file_get_contents($file), true) ?: [];
}

$data[] = [
  'name'  => $name,
  'role'  => $role ?: 'Cliente',
  'rating'=> $rating,
  'text'  => $text,
  'date'  => date('Y-m-d H:i')
];

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) === false) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'storage_unavailable']);
  exit;
}

echo json_encode(['ok'=>true,'message'=>'saved']);

