<?php

require_once dirname(__DIR__, 2) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Requête non autorisée';
    exit;
}

// En local, Origin peut être absent ; en prod on le conserve si présent.
$isLocal = env('APP_ENV', 'local') === 'local';
if (!$isLocal && !isset($_SERVER['HTTP_ORIGIN'])) {
    http_response_code(403);
    echo 'Origine non autorisée';
    exit;
}

if (!isset($_POST['honeypot']) || $_POST['honeypot'] !== '') {
    http_response_code(405);
    echo 'Requête non autorisée';
    exit;
}

if (preg_match('!^ *$!s', $_POST['mail'] ?? '') || preg_match('!^ *$!s', $_POST['password'] ?? '')) {
    header('Location: ../login.php?login=empty');
    exit;
}

$mail = htmlspecialchars((string) $_POST['mail']);
$password = hash('sha512', htmlspecialchars((string) $_POST['password']));

if ($mail === '' || $password === '') {
    header('location: ../settings.php?login=failure');
    exit;
}

$sql = 'SELECT COUNT(*) AS total FROM users WHERE mail=:mail AND password=:password';
$query = $db->prepare($sql);
$query->bindValue(':mail', $mail, PDO::PARAM_STR);
$query->bindValue(':password', $password, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch();

if ((int) ($user['total'] ?? 0) === 1) {
    $req = $db->prepare('SELECT * FROM users WHERE mail=:mail');
    $req->execute(['mail' => $mail]);
    $data = $req->fetch();

    session_start();
    $_SESSION['user'] = [
        'pseudo' => $data['pseudo'],
        'token' => $data['token'],
        'id' => $data['id'],
        'role' => $data['id_level'],
    ];

    header('location: ../myaccount.php?login=success');
    exit;
}

header('location: ../settings.php?login=wrong');
