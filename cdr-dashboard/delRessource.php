<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    require_once 'ressources/php/db.php';
    require_once 'ressources/php/functions.php';


?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/gestions.css">
        <title>CDR - Mon compte</title>
    </head>

    <body>

        <?php
        require_once 'template/sidebar.php';
        ?>
        <main>
            <header>
                <h1>Suppression d'une ressource</h1>
            </header>
            <?php
            if (isset($_GET['login'])) {
                if ($_GET['login'] == 'success') {
                    echo '<div class="success">Vous êtes connecté !</div>';
                }
            }
            ?>
            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $req = $db->prepare('SELECT r.id, r.date, r.title, r.subtitle, r.content, r.image, c.nom AS category, u.pseudo AS auteur, s.nom AS structure
                    FROM ressources r
                    INNER JOIN categories c ON r.id_categories = c.id
                    INNER JOIN users u ON r.id_users = u.id
                    INNER JOIN structure s ON r.id_structure = s.id
                    WHERE r.id = :id');
                $req->execute([':id' => $id]);
                $res = $req->fetch(PDO::FETCH_ASSOC);
            }
            ?>
            <h1>Êtes-vous sûr de vouloir supprimer la ressource suivante ?</h1>

            <alert><?= $res['title']; ?></alert>
            <alert><?= $res['auteur']; ?></alert>

            <div class="btns">
                <a href="../traitements/delRessource.php?id=<?= $id; ?>" class="btn-link">
                    Oui
                </a>
                <a href="gestionRessources.php?del=cancel" class="btn-link">
                    Non
                </a>
            </div>
        </main>

    </body>

    </html>
<?php
} else {
    header('location: login.php');
}


?>