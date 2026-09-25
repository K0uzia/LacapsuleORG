<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$q = trim((string) ($_GET['q'] ?? ''));
if (mb_strlen($q) < 2) {
    echo json_encode(['q' => $q, 'items' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$items = search_all_ressources($db, $q, 12);
echo json_encode(['q' => $q, 'items' => $items], JSON_UNESCAPED_UNICODE);
