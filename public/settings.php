<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php $pageTitle = 'Profil professionnel'; view('head.php'); ?>
</head>

<body ontouchstart="">
    <?php
    view('header.php');
    ?>
    <alert><i>Cette partie du site est destinée
            aux professionnels du numérique.</i></alert>
    <form action="traitements/connect.php" method="POST">
        <h2>Connexion</h2>
        <?php
        if (!isset($_SESSION['user'])) {
            if (isset($_GET['login'])) {
                if ($_GET['login'] === 'failure') {
                    echo '<alert><i>Identifiants incorrects</i></alert>';
                }
            }
        ?>
            <input type="email" name="mail" placeholder="Votre adresse mail">
            <input type="password" name="password" placeholder="Votre mot de passe">
            <input type="hidden" name="honeypot">
            <input type="submit">
            <hr>
            <hr>
            <p><a href="contact.php">Pas encore inscrit ?</a></p>
            <p><a href="forget.php">Mot de passe oublié ?</a></p>
    </form>
<?php
        } else {
            header('Location: myaccount.php');
        }
        view('footer.php');
?>
</body>

</html>