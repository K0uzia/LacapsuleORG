<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];
    $role = $_SESSION['user']['role'];
}
require_once dirname(__DIR__) . '/bootstrap.php';


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php $pageTitle = 'CDR - Mon compte'; view('head.php'); ?>
</head>

<body>

    <?php
    view('header.php');
    if (isset($_GET['login'])) {
        if ($_GET['login'] == 'success') {
            echo '<div class="success">Vous êtes connecté !</div>';
        }
    }
    ?>
    <span>
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $req = $db->prepare('SELECT r.id, r.date, r.title, r.subtitle, r.content, r.image, r.deroule, r.tuto, c.name AS category, u.nom AS auteur, s.nom AS structure
            FROM ressources r
            INNER JOIN categories c ON r.id_categories = c.id
            INNER JOIN users u ON r.id_users = u.id
            INNER JOIN stucture s ON r.id_stucture = s.id
            WHERE r.id = :id');
            $req->execute([':id' => $id]);
            $res = $req->fetch(PDO::FETCH_ASSOC);
            echo '<h1>Voulez-vous vraiment supprimer la ressource suivante ?</h1>';
            echo '<h2>Titre : ' . $res['title'] . '</h2>';
            echo '<h3>Sous-Titre : ' . $res['subtitle'] . '</h3>';
            echo '<h4>Contenu : ' . $res['content'] . '</h4>';
            echo '<div class="btns">';
            echo '<button class="btn">
            <a href="traitements/delRessource.php?id=' . $res['id'] . '">Confirmer</a>
            </button>';
            echo '<button class="btn">
            <a href="myressources.php?del=cancel">Annuler</a>
            </button>';
            echo '</div>';
        }
        ?>
    </span>
    <?php
    view('footer.php');
    ?>


</body>

</html>