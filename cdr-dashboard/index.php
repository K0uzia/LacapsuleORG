<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    require_once 'ressources/php/functions.php';




    $lastUser = last('users', $db, 'id');
    $lastRessource = last('ressources', $db, 'id');
    $myAccount = getMyAccount($db, $id);
?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Accueil</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <?php
        if (isset($_GET)) {
            switch ($_GET) {
                case 'connect':
                    switch ($_GET['connect']) {
                        case 'success':
                            echo '<p class="success">Connexion réussie</p>';
                            break;
                    }
            }
        }
        require 'template/sidebar.php';
        ?>
        <main id="dash">
            <header>
                <h1>Dashboard - Accueil</h1>
            </header>
            <section id="recap">
                <span class="nbRes">
                    <h2>Nombre de ressources publiées</h2>
                    <p class="nb">
                        <?= numb('ressources', $db) ?>
                    </p>
                </span>
                <span class="nbStr">
                    <h2>Nombre de structures actives</h2>
                    <p class="nb">
                        <?= numb('structure', $db) ?>
                    </p>
                </span>
                <span class="nbUsers">
                    <h2>Nombre d'utilisateurs actifs</h2>
                    <p class="nb">
                        <?= numb('users', $db) ?>
                    </p>
                </span>
                <span class="derPub">
                    <h2>Dernière publication de ressource</h2>
                    <p>
                        <?= $lastRessource['title'] ?>
                    </p>
                </span>
                <span class="bienvenue">
                    <h2>Souhaitons la bienvenue à <name><?= $lastUser['pseudo'] ?></name> de la structure <name><?= $lastUser['structure'] ?></name> qui vient de nous rejoindre.</h2>
                </span>
                <span class="monRecap">
                    <h2>Votre récpitulatif personnel</h2>
                    <h3>Vous avez publié <nombre><?= nbMyRessources($db, $id) ?></nombre> ressources depuis votre inscription le <date><?= date('d-m-Y', strtotime($myAccount['dateInscription'])) ?> </date>.</h3>
                    <h3>Vos ressources ont été consultées <nombre>XXX</nombre> fois.</h3>
                    <h3>Votre catégorie favorite est <catégorie>XXX</catégorie>.</h3>
                    <h4>La ressource qui a eu le plus de succès est <a href="../viewRessource.php?id=<?= $lastRessource['id'] ?>"><?= $lastRessource['title'] ?></a></h4>
                    <h4>Elle a été consultée <nombre><?= $lastRessource['views'] ?></nombre> fois.</h4>
                </span>
            </section>
            <section id="mesPub">
                <span class="statPub">
                    <h2>Statistiques des publications</h2>
                </span>
                <span class="derRes">
                    <h3>Aperçu de la dernière ressource publiée</h3>
                    <h4><?= $lastRessource['title'] ?></h4>
                    <img src="../uploads/img/<?= $lastRessource['image'] ?>" alt="<?= $lastRessource['title'] ?>">
                    <a href="../viewRessource.php?id=<?= $lastRessource['id'] ?>" style="text-decoration:none;">Voir la ressource</a>
                </span>
            </section>
        </main>
    </body>

    </html>

<?php
} else {
    header('location: login.php');
}
?>