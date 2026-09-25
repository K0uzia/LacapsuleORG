<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
$pageTitle = 'Connexion | La Capsule';
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
            <h1>Connexion</h1>
            <p style="color:var(--lcap-muted)">Espace réservé aux contributeurs et professionnels du numérique.</p>
            <?php if (isset($_GET['login']) && $_GET['login'] === 'empty'): ?>
                <p class="alert">Merci de renseigner e-mail et mot de passe.</p>
            <?php elseif (isset($_GET['login']) && in_array($_GET['login'], ['failure', 'wrong'], true)): ?>
                <p class="alert">Identifiants incorrects.</p>
            <?php endif; ?>
            <?php if (!isset($_SESSION['user'])): ?>
            <form action="traitements/connect.php" method="post">
                <label for="mail">Adresse e-mail</label>
                <input id="mail" type="email" name="mail" required autocomplete="username">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                <input type="hidden" name="honeypot" value="">
                <button class="btn btn--primary" type="submit" style="width:100%;margin-top:0.5rem">
                    <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Se connecter
                </button>
            </form>
            <p style="margin-top:1rem"><a href="contact.php">Pas encore inscrit ?</a></p>
            <?php else: ?>
                <p>Tu es déjà connecté·e.</p>
                <a class="btn btn--primary" href="myaccount.php">Mon compte</a>
            <?php endif; ?>
        </div>
    </main>
    <?php view('footer.php'); ?>
</body>
</html>
