<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            if (!preg_match('!^ *$!s', $_POST['nom']) && !preg_match('!^ *$!s', $_POST['prenom']) && !preg_match('!^ *$!s', $_POST['mail'])) {
                if (isset($_GET['id'])) {
                    $nom = htmlspecialchars($_POST['nom']);
                    $prenom = htmlspecialchars($_POST['prenom']);
                    $mail = htmlspecialchars($_POST['mail']);
                    $id = $_GET['id'];
                    $role = htmlspecialchars($_POST['level']);
                    $pseudo = htmlspecialchars($_POST['pseudo']);
                    require_once '../ressources/php/db.php';

                    $req = $db->prepare('UPDATE users SET nom = :nom, prenom = :prenom, mail = :mail, id_level = :role, pseudo = :pseudo WHERE id = :id');
                    $req->execute([':nom' => $nom, ':prenom' => $prenom, ':mail' => $mail, ':role' => $role, ':pseudo' => $pseudo, ':id' => $id]);
                    header('location: ../gestionUsers.php?update=success');
                } else {
                    header('location: ../gestionUsers.php?update=userNotFound');
                }
            } else {
                header('location: ../gestion.php?update=emptyFields');
            }
        } else {
            http_response_code(405);
            echo 'Requête non autorisée !';
        }
    }
}
