<footer>
<menu class="footer_gauche">
        <a href="#"><img src="https://lacapsule.org/media/assets/logo/projet-europeen.webp" alt="Logo de l'Intereg" title="Financement"></a>
        <a href="#"><img src="https://lacapsule.org/media/assets/logo/departement.webp" alt="Logo du département Finistère" title="Financement"></a>
    </menu>
    <menu class="footer_droite">
        <a href="contact.php"><img class="link" src="assets/img/icons/mail.png" alt="Contact" title="Contact"></a>
        <a href="myaccount.php"><img class="link" src="assets/img/icons/user.png" alt="Paramètres" title="Paramètres"></a>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<a href="traitements/logout.php"><img class="link" src="assets/img/icons/lock.png" alt="Se deconnecter" title="Se deconnecter"></a>';
        }
        ?>
    </menu>
</footer>