<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];

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
        if (isset($_GET)) {
            switch (key($_GET)) {
                case 'login':
                    switch ($_GET['login']) {
                        case 'success':
                            echo '<p class="success">Connexion réussie</p>';
                            break;
                    }
                    break;
                case 'update':
                    switch ($_GET['update']) {
                        case 'success':
                            echo '<p class="success">Modification réussie</p>';
                            break;
                        case 'failure':
                            echo '<p class="err">Modification échouée</p>';
                            break;
                    }
                    break;
                case 'updatePassword':
                    switch ($_GET['updatePassword']) {
                        case 'success':
                            echo '<p class="success">Mot de passe modifié</p>';
                            break;
                        case 'failure':
                            echo '<p class="err">Les deux mots de passe ne correspondent pas</p>';
                            break;
                    }
                    break;
            }
        }

        ?>
        <span>
            <h1>Mon compte</h1>
            <h2>Bienvenue <?php echo $user; ?></h2>
            <h3>Voici vos informations</h3>
            <?php
            $req = $db->prepare('SELECT * FROM users WHERE token = ?');
            $req->execute(array($token));
            $data = $req->fetch();
            ?>
            <dl>
                <a href="myressources.php">Voir mes ressources</a>
                <a href="postressources.php">Poster une ressource</a>
                <a href="updatePassword.php?id=<?= $data['id']; ?>">Modifier mon mot de passe</a>
                <?php
                if ($data['id_level'] == 3) {
                    echo '<a href="cdr-dashboard">Dashboard administratif</a>';
                }
                ?>
                <a href="traitements/logout.php">Se déconnecter</a>
            </dl>
        </span>


        <form action="traitements/updateUser.php?id=<?= $data['id']; ?>" method="POST">
            <h2>Modifier mes informations</h2>
            <input type="text" name="prenom" placeholder="Prénom" value="<?php echo $data['prenom']; ?>">
            <input type="text" name="nom" placeholder="Nom" value="<?php echo $data['nom']; ?>">
            <input type="text" name="pseudo" placeholder="Pseudo" value="<?php echo $data['pseudo']; ?>">
            <input type="email" name="mail" placeholder="Email" value="<?php echo $data['mail']; ?>">
            <input type="hidden" name="honeypot">
            <input type="submit">
        </form>
        <?php
        view('footer.php');
        ?>


    </body>

    </html>
<?php
} else {
    header('Location: login.php');
}
?>