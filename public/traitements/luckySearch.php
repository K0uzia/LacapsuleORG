<?php

require_once dirname(__DIR__, 2) . '/bootstrap.php';

$order = DB_DRIVER === 'sqlite' ? 'RANDOM()' : 'RAND()';
$req = $db->query("SELECT id, slug FROM ressources WHERE slug != '' ORDER BY {$order} LIMIT 1");
$res = $req->fetch(PDO::FETCH_OBJ);

if (!$res || empty($res->slug)) {
    header('Location: ../index.php?error=ressourceNotFound');
    exit;
}

header('Location: ../viewRessource.php?q=' . urlencode($res->slug));
