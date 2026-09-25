<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            $nom = $_POST['nom'];
            $id = $_GET['id'];
            require_once '../ressources/php/db.php';
            $req = $db->prepare('SELECT * FROM categories WHERE nom = :nom');
            $req->execute([':nom' => $nom]);
            $res = $req->fetch(PDO::FETCH_ASSOC);
            if ($res) {
                header('Location: ../updateCategorie.php?id=' . $id . '&update=failure');
            } else {
                $req = $db->prepare('UPDATE categories SET nom = :nom WHERE id = :id');
                $req->execute([':nom' => $nom, ':id' => $id]);
                header('Location: ../gestionCategories.php?update=success');
            }
        }
    }
}
