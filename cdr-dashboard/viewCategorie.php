<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once 'ressources/php/db.php';
        $id = $_GET['id'];
        $req = $db->prepare('SELECT * FROM categories WHERE id = :id');
        $req->execute([':id' => $id]);
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $nom = $res['nom'];

?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard - Ajouter un utilisateur</title>
            <link rel="stylesheet" href="css/gestions.css">
            <link rel="stylesheet" href="css/style.css">
        </head>

        <body>
            <?php
            require 'template/sidebar.php';
            if (isset($_GET['add'])) {
                if ($_GET['add'] === 'failure') {
            ?>
                    <p class="err">La catégorie existe déjà</p>
            <?php
                }
            }

            ?>

            <main>
            <header>
            <h1>Informations de la catégorie</h1>
        </header>
                <h1>Informations de la catégorie</h1>
                <alert>Id: <?= $id; ?></alert>
                <alert>Nom: <?= $nom; ?></alert>

                <div class="btns">
                    <button class="btn">
                        <a href="updateCategorie.php?id=<?= $id; ?>">
                            Modifier la catégorie
                        </a>
                    </button>
                    <button class="btn">
                        <a href="delCategorie.php?id=<?= $id; ?>">
                            Supprimer la catégorie
                        </a>
                    </button>
                </div>

            </main>
        </body>

        </html>
<?php

    } else {
        header('Location: gestionCategories.php');
    }
} else {
    header('location: login.php');
}
