<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once 'ressources/php/db.php';
        $id = $_GET['id'];
        $req = $db->prepare('SELECT * FROM structure WHERE id = :id');
        $req->execute([':id' => $id]);
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $structure = [
            'id' => $res['id'],
            'nom' => $res['nom'],
            'adresse' => $res['adresse'],
            'cp' => $res['codePostal'],
            'ville' => $res['ville'],
            'mail' => $res['mail'],
            'telephone' => $res['telephone'],
            'referent' => $res['referent']
        ];

?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard - Informations utlisateur</title>
            <link rel="stylesheet" href="css/gestions.css">
            <link rel="stylesheet" href="css/style.css">
        </head>

        <body>
            <?php
            require 'template/sidebar.php';
            ?>

            <main>
            <header>
            <h1>Informations de la structure</h1>
        </header>
                <alert>Id: <strong><?= $structure['id']; ?></strong></alert>
                <alert>Nom: <?= $structure['nom']; ?></alert>
                <alert>Adresse postale: <?= $structure['adresse']; ?></alert>
                <alert>Code postal: <?= $structure['cp']; ?></alert>
                <alert>Ville: <?= $structure['ville']; ?></alert>
                <alert>Mail: <?= $structure['mail']; ?></alert>
                <alert>Téléphone: <?= $structure['telephone']; ?></alert>
                <alert>Référent: <?= $structure['referent']; ?></alert>



                <div class="btns">
                    <button class="btn">
                        <a href="updateStructure.php?id=<?= $id; ?>">
                            Modifier la structure
                        </a>
                    </button>
                    <button class="btn">
                        <a href="delStructure.php?id=<?= $id; ?>">
                            Supprimer la structure
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
    header('Location: login.php');
}
