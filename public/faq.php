<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
$pageTitle = 'FAQ | La Capsule';
$faq = require dirname(__DIR__) . '/app/data/faq.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
    <?php view('header.php'); ?>
    <main>
        <header class="section" style="margin-top:0">
            <h1 class="section__title">FAQ La Capsule</h1>
            <p class="section__lead">Contenu issu de lacapsule.org/FAQ_lacapsule.html.</p>
        </header>
        <?php view('faq-block.php'); ?>
    </main>
    <?php view('footer.php'); ?>
</body>
</html>
