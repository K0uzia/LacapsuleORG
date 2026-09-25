<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            require_once '../ressources/php/db.php';

            $categorie = htmlspecialchars($_POST['nom']);

            $sql = 'SELECT COUNT(*) FROM categories WHERE nom=:categorie';
            $query = $db->prepare($sql);
            $query->bindValue(':categorie', $categorie, PDO::PARAM_STR);
            $query->execute();
            $check = $query->fetch();

            if ($check['COUNT(*)'] === 0) {
                $request = 'INSERT INTO categories(nom) VALUES(?)';
                $insert = $db->prepare($request);
                $exec = $insert->execute(array($categorie));

                header('location: ../gestionCategories.php?add=success');
            } else {
                header('location: ../addCategorie.php?add=failure');
            }
        }
    }
}
