<footer class="site-footer">
    <div class="site-footer__grid">
        <div class="site-footer__brand">
            <p class="site-footer__title"><i class="fa-solid fa-cube" aria-hidden="true"></i> La Capsule</p>
            <p class="site-footer__text">
                Chantier d'insertion numérique à Morlaix, porté par l'ULAMIR-CPIE Pays de Morlaix.
            </p>
        </div>
        <div>
            <p class="site-footer__heading">Explorer</p>
            <ul class="site-footer__list">
                <li><a href="index.php#services">Services</a></li>
                <li><a href="index.php#ressources">Ressources</a></li>
                <li><a href="index.php#productions">Productions</a></li>
                <li><a href="index.php#faq-home">FAQ</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </div>
        <div>
            <p class="site-footer__heading">Écosystème</p>
            <ul class="site-footer__list">
                <li><a href="https://lacapsule.org/" target="_blank" rel="noopener">lacapsule.org</a></li>
                <li><a href="https://os.lacapsule.org/" target="_blank" rel="noopener">CapsuleOS</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="myaccount.php">Mon compte</a></li>
                    <li><a href="traitements/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="site-footer__bottom">
        <p>© <?= date('Y') ?> La Capsule | ULAMIR-CPIE Pays de Morlaix</p>
    </div>
</footer>
<?php view('modal.php'); ?>
<script src="assets/js/main.js" defer></script>
