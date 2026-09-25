<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];
    $role = $_SESSION['user']['role'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php $pageTitle = 'La Capsule CDR'; view('head.php'); ?>
</head>

<body ontouchstart="">
    <?php
    view('header.php');
    ?>
    <main>
        <form action="result.php" method="post">
            <h1>Rechercher une ressource</h1>
            <h2>Taper une ressource, une catégorie, une structure, etc...</h2>
            <input type="text" name="search" placeholder="Rechercher..." autofocus>
            <input type="submit" value="Rechercher">
        </form>
    </main>
    <?php
    view('footer.php');
    ?>
</body>

</html>