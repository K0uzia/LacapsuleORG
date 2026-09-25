<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once dirname(__DIR__) . '/bootstrap.php';
$id = $_SESSION['user']['id'];
$pageTitle = 'Modifier le mot de passe | La Capsule';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
    <?php view('header.php'); ?>
    <main>
        <div class="page-card" style="max-width:420px;margin:0 auto">
            <h1>Mot de passe</h1>
            <form action="traitements/updatePassword.php?id=<?= (int) $id ?>" method="post">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required>
                <label for="passwordverify">Confirmation</label>
                <input type="password" name="passwordverify" id="passwordverify" required>
                <input type="hidden" name="honeypot" value="">
                <button class="btn btn--primary" type="submit" style="width:100%">Enregistrer</button>
            </form>
        </div>
    </main>
    <?php view('footer.php'); ?>
</body>
</html>
