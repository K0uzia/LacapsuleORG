<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once '../ressources/php/db.php';
        $id = $_GET['id'];
        $req = $db->prepare('SELECT * FROM users WHERE id = :id');
        $req->execute([':id' => $id]);
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $user = [
            'id' => $res['id'],
            'nom' => $res['nom'],
            'prenom' => $res['prenom'],
            'pseudo' => $res['pseudo'],
            'mail' => $res['mail'],
            'level' => $res['id_level']
        ];

?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard - Modifier un utilisateur</title>
            <link rel="stylesheet" href="css/gestions.css">
            <link rel="stylesheet" href="css/style.css">
        </head>

        <body>
            <?php
            require 'template/sidebar.php';
            if (isset($_GET['add'])) {
                if ($_GET['add'] === 'success') {
            ?>
                    <span class="success">Utilisateur ajouté</span>
                <?php
                }
                if ($_GET['add'] === 'pseudoexist') {
                ?>
                    <span class="err">Un compte avec ce pseudo existe déjà</span>
                <?php
                }
                if ($_GET['add'] === 'mailexist') {
                ?>
                    <span class="err">Un compte avec cette adresse mail existe déjà</span>
            <?php
                }
            }

            ?>

            <main>
            <header>
                <h1>Modification d'un utilisateur</h1>
            </header>
                <form action="traitements/updateUser.php?id=<?= $user['id']; ?>" method="post">
                    <input type="name" name="nom" id="nom" placeholder="Nom" value="<?= $user['nom']; ?>" required>
                    <input type="name" name="prenom" id="prenom" placeholder="Prénom" value="<?= $user['prenom']; ?>" required>
                    <input type="email" name="mail" id="mail" placeholder="Mail" value="<?= $user['mail']; ?>" required>
                    <input type="name" name="pseudo" id="pseudo" placeholder="Pseudo" value="<?= $user['pseudo']; ?>" required>
                    <label for="level">Rôle de l'utilisateur</label>
                    <select name="level" id="level" required>
                        <?php
                        switch ($user['level']) {
                            case 2:
                                echo '<option value="2" selected>Professionnel</option>';
                                echo '<option value="3">Administrateur</option>';
                                break;
                            case 3:
                                echo '<option value="2">Professionnel</option>';
                                echo '<option value="3" selected>Administrateur</option>';
                                break;
                        }
                        ?>
                    </select>
                    <input type="hidden" name="honeypot">
                    <div class="btns">
                        <button type="submit">Modifier l'utilisateur</button>
                    </div>
                </form>
            </main>
        </body>

        </html>
<?php

    } else {
        header('Location: gestionUsers.php');
    }
} else {
    header('Location: ../login.php');
}
?>