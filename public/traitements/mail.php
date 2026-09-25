<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Requête non autorisée';
    exit;
}

$isLocal = env('APP_ENV', 'local') === 'local';
if (!$isLocal && !isset($_SERVER['HTTP_ORIGIN'])) {
    http_response_code(403);
    exit;
}

if (!isset($_POST['honeypot']) || $_POST['honeypot'] !== '') {
    http_response_code(405);
    echo 'Requête non autorisée';
    exit;
}

$mail = trim((string) ($_POST['mail'] ?? ''));
$nom = trim((string) ($_POST['nom'] ?? ''));
$prenom = trim((string) ($_POST['prenom'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? $_POST['sujet'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($mail === '' || $nom === '' || $prenom === '' || $subject === '' || $message === '') {
    header('Location: ../index.php?send=failure#contact');
    exit;
}

// En local on ne bloque pas si mail() est indisponible
@mail(
    'site@lacapsule.org',
    'Contact CDR : ' . $subject,
    "De: $prenom $nom <$mail>\n\n" . $message,
    'From: webmaster@lacapsule.org'
);

header('Location: ../index.php?send=success#contact');
