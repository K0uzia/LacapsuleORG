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
                <h1>Modification d'une catégorie</h1>
            </header>
                <form action="traitements/updateCategorie.php?id=<?= $id; ?>" method="post">
                    <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= $nom; ?>" required>
                    <input type="hidden" name="honeypot">
                    <div class="btns">
                        <button type="submit">Modifier la catégorie</button>
                    </div>
                </form>
            </main>
        </body>

        </html>
<?php

    } else {
        header('Location: gestionCategories.php?update=nofound');
    }
} else {
    header('location: login.php');
}
