<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];
    $id = $_SESSION['user']['id'];
    require_once dirname(__DIR__) . '/bootstrap.php';
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <?php $pageTitle = 'CDR - Mes ressources'; view('head.php'); ?>
    </head>

    <body>
        <?php
        view('header.php');
        if (isset($_GET)) {
            switch (key($_GET)) {
                case 'del':
                    switch ($_GET['del']) {
                        case 'success':
                            echo '<p class="success">La ressource a été supprimée</p>';
                            break;
                        case 'cancel':
                            echo '<p class="cancel">Suppression de la ressource annulée</p>';
                    }
            }
        }
        ?>
        <aside class="categories">
            <h2 class="titre">Bienvenue <?php echo $user; ?>, voici vos ressources :</h2>
        </aside>
        <?php
        $sql = "SELECT COUNT(*) FROM ressources WHERE id_users=:id";
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();

        if ($result['COUNT(*)'] === 0) {
            echo 'Vous n\'avez publié aucunes ressources';
        } else {
            $req = $db->query('SELECT * FROM ressources WHERE id_users="' . $id . '"');
            while ($res = $req->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <span class="maRessource">
                    <h3 class="contenuRessource"><?= $res['title']; ?></h3>
                    <img class="contenuRessource" src="uploads/img/thumbnails/<?= $res['image']; ?>" alt="<?= $res['title']; ?>">
                    <a class="contenuRessource" href="viewRessource.php?q=<?= $res['slug'] ?>">Voir la ressource</a>
                    <a class="contenuRessource" href="updateRessource.php?q=<?= $res['slug']; ?>">Modifier la ressource</a>
                    <a class="contenuRessource" href="delRessource.php?id=<?= $res['id']; ?>">Supprimer la ressource</a>
                </span>
        <?php

            }
            $req->closeCursor();
        }
        ?>
        <?php
        view('footer.php');
        ?>
    </body>

    </html>
<?php
} else {
    header('Location: index.php');
}
?>