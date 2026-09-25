<?php
session_start();
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            $id = $_SESSION['user']['id'];
            $password = htmlspecialchars($_POST['password']);
            $passwordhash = hash('sha512', $password);
            $passwordverify = htmlspecialchars($_POST['passwordverify']);
            if ($password === $passwordverify) {
                require_once dirname(__DIR__, 2) . '/bootstrap.php';
                $req = $db->prepare('UPDATE users SET password = :password WHERE id = :id');
                $req->execute([
                    ':password' => $passwordhash,
                    ':id' => $id
                ]);
                header('Location: ../myaccount.php?updatePassword=success');
            } else {
                header('Location: ../updatePassword.php?updatePassword=failure');
            }
        }
    }
}
