<?php
$script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$isHome = $script === 'index.php';
$loggedIn = isset($_SESSION['user']);
$prefix = $isHome ? '' : 'index.php';
$phoneDisplay = '07 86 44 77 52';
$phoneTel = '+33786447752';
?>
<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="<?= $isHome ? '#top' : 'index.php' ?>" aria-label="La Capsule accueil">
            <img class="brand__logo" src="assets/img/brand/logo_lacapsule.webp" width="48" height="48" alt="La Capsule">
            <span class="brand__text">
                <span class="brand__name">La Capsule</span>
                <span class="brand__tag">Insertion numérique · Morlaix</span>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Ouvrir le menu">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        <nav id="site-nav" class="site-nav" aria-label="Navigation principale">
            <a class="site-nav__link" href="<?= $prefix ?>#services">Services</a>
            <a class="site-nav__link" href="<?= $prefix ?>#ressources">Ressources</a>
            <a class="site-nav__link" href="<?= $prefix ?>#productions">Productions</a>
            <a class="site-nav__link" href="<?= $prefix ?>#partenaires">Partenaires</a>
            <a class="site-nav__link" href="<?= $prefix ?>#faq-home">FAQ</a>
            <a class="site-nav__link" href="<?= $prefix ?>#contact">Contact</a>
            <a class="site-nav__link" href="https://os.lacapsule.org/" target="_blank" rel="noopener">Os</a>
            <?php if ($loggedIn): ?>
                <a class="site-nav__cta" href="myaccount.php"><i class="fa-solid fa-user" aria-hidden="true"></i> Compte</a>
            <?php else: ?>
                <a class="site-nav__cta" href="login.php"><i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Connexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="top-banner" role="region" aria-label="Horaires et contact">
    <div class="top-banner__inner">
        <span class="top-banner__item">
            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
            39 Bellevue de la Madeleine, 29600 Morlaix
        </span>
        <span class="top-banner__item top-banner__item--accent">
            <i class="fa-solid fa-door-open" aria-hidden="true"></i>
            Ouvert au public : vendredi 14h-17h
        </span>
        <span class="top-banner__item">
            <i class="fa-solid fa-clock" aria-hidden="true"></i>
            Équipe : lun · mar · jeu · ven 9h-17h
        </span>
        <a class="top-banner__item" href="tel:<?= htmlspecialchars($phoneTel) ?>">
            <i class="fa-solid fa-phone" aria-hidden="true"></i>
            <?= htmlspecialchars($phoneDisplay) ?>
        </a>
        <a class="top-banner__item" href="<?= $prefix ?>#contact">
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            Nous écrire
        </a>
    </div>
</div>
