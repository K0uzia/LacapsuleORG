<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            if (!empty($_POST['mail']) && !empty($_POST['password'])) {
                $mail = htmlspecialchars($_POST['mail']);
                $password =  hash('SHA512', htmlspecialchars($_POST['password']));
                require_once '../ressources/php/db.php';
                $req = $db->prepare('SELECT * FROM users WHERE mail = :mail AND password = :password');
                $req->execute([':mail' => $mail, ':password' => $password]);
                $res = $req->fetch(PDO::FETCH_ASSOC);
                if ($res) {
                    if ($res['id_level'] === 2) {
                        session_start();
                        $_SESSION['user'] = [
                            'pseudo' => $res['pseudo'],
                            'token' => $res['token'],
                            'id' => $res['id'],
                            'role' => $res['id_level']
                        ];
                        header('location: ../index.php?connect=success');
                    } else {
                        header('location: ../login.php?connect=noadmin');
                    }
                } else {
                    header('location: ../login.php?connect=failure');
                }
            }
        }
    }
}
