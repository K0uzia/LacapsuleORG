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
        if ($res) {
            $nom = $res['nom'];

?>

            <!DOCTYPE html>
            <html lang="fr">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Dashboard - Supprimer une catégorie</title>
                <link rel="stylesheet" href="css/gestions.css">
                <link rel="stylesheet" href="css/style.css">
            </head>

            <body>
                <?php
                require 'template/sidebar.php';
                ?>

                <main>
            <header>
            <h1>Suppression d'une structure</h1>
        </header>
                    <h1>Êtes-vous sûr de vouloir supprimer la structure suivante ?</h1>

                    <alert><?= $nom; ?></alert>

                    <div class="btns">
                        <a href="traitements/delStructure.php?id=<?= $id; ?>" class="btn-link">
                            Oui
                        </a>
                        <a href="gestionStructures.php?del=cancel" class="btn-link">
                            Non
                        </a>
                    </div>
                </main>
            </body>

            </html>
<?php
        } else {
            header('location: gestionStructures.php?del=nofound');
        }
    } else {
        header('Location: gestionStructures.php?del=nofound');
    }
} else {
    header('location: login.php');
}
