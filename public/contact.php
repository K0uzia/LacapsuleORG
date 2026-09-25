<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
$pageTitle = 'Contact | La Capsule';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
    <?php view('header.php'); ?>
    <main>
        <?php if (isset($_GET['send'])): ?>
            <p class="alert">
                <?= $_GET['send'] === 'success'
                    ? 'Votre message a bien été envoyé.'
                    : 'Tous les champs doivent être renseignés.' ?>
            </p>
        <?php endif; ?>

        <div class="page-card" style="display:grid;gap:1.5rem;grid-template-columns:1.2fr 0.8fr">
            <div>
                <h1>Contact</h1>
                <form action="traitements/mail.php" method="post">
                    <label for="mail">E-mail</label>
                    <input id="mail" type="email" name="mail" required>
                    <label for="nom">Nom</label>
                    <input id="nom" type="text" name="nom" required>
                    <label for="prenom">Prénom</label>
                    <input id="prenom" type="text" name="prenom" required>
                    <label for="subject">Sujet</label>
                    <input id="subject" type="text" name="subject" required>
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                    <input type="hidden" name="honeypot" value="">
                    <button class="btn btn--primary" type="submit">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Envoyer
                    </button>
                </form>
            </div>
            <aside>
                <h2 style="font-family:var(--font);font-weight:700;margin-top:0">La Capsule</h2>
                <p style="color:var(--lcap-muted)">
                    Chantier d’insertion numérique, ULAMIR-CPIE Pays de Morlaix.
                </p>
                <p><a href="https://lacapsule.org/" target="_blank" rel="noopener"><i class="fa-solid fa-globe" aria-hidden="true"></i> lacapsule.org</a></p>
                <p><a href="https://os.lacapsule.org/" target="_blank" rel="noopener"><i class="fa-solid fa-desktop" aria-hidden="true"></i> CapsuleOS</a></p>
                <p><a href="faq.php"><i class="fa-solid fa-circle-question" aria-hidden="true"></i> Voir la FAQ</a></p>
            </aside>
        </div>
    </main>
    <?php view('footer.php'); ?>
</body>
</html>
