<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
                if (!preg_match('!^ *$!s', $_POST['prenom']) && !preg_match('!^ *$!s', $_POST['nom']) && !preg_match('!^ *$!s', $_POST['pseudo']) && !preg_match('!^ *$!s', $_POST['mail'])) {
                    $prenom = htmlspecialchars($_POST['prenom']);
                    $nom = htmlspecialchars($_POST['nom']);
                    $pseudo = htmlspecialchars($_POST['pseudo']);
                    $mail = htmlspecialchars($_POST['mail']);
                    require_once dirname(__DIR__, 2) . '/bootstrap.php';
                    $req = $db->prepare('UPDATE users SET prenom = ?, nom = ?, pseudo = ?, mail = ? WHERE id = ?');
                    $req->execute(array($prenom, $nom, $pseudo, $mail, $id));
                    header('location: ../myaccount.php?update=success');
                } else {
                    header('location: ../myaccount.php?update=failure');
                }
            } else {
                http_response_code(405);
                echo 'Requête non autorisée !';
            }
        }
    }
} else {
    header('location: ../index.php');
}
