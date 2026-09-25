<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once 'ressources/php/db.php';
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
            'token' => $res['token'],
            'level' => $res['id_level'],
            'statement' => $res['id_statement'],
        ];

?>

        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard - Informations utlisateur</title>
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/gestions.css">
        </head>

        <body>
            <?php
            require 'template/sidebar.php';
            ?>

            <main>
                
      <header>
            <h1>Informations de l'utilisateur</h1>
        </header>
                <alert>Id: <?= $user['id']; ?></alert>
                <alert>Nom: <?= $user['nom']; ?></alert>
                <alert>Prenom: <?= $user['prenom']; ?></alert>
                <alert>Pseudo: <?= $user['pseudo']; ?></alert>
                <alert>Mail: <?= $user['mail']; ?></alert>
                <alert>Statement:
                    <?php
                    switch ($user['statement']) {
                        case 1:
                            echo 'En attente';
                            break;
                        case 2:
                            echo 'Validé';
                            break;
                        case 3:
                            echo 'Recovery';
                            break;
                    }
                    $user['nom'];
                    ?></alert>
                <alert>Role:
                    <?php
                    switch ($user['level']) {
                        case 1:
                            echo 'Utilisateur';
                            break;
                        case 2:
                            echo 'Administrateur';
                            break;
                    }
                    ?>
                </alert>


                <div class="btns">
                    <button class="btn">
                        <a href="updateUser.php?id=<?= $id; ?>">
                            Modifier l'utilisateur
                        </a>
                    </button>
                    <button class="btn">
                        <a href="delUSer.php?id=<?= $id; ?>">
                            Supprimer l'utilisateur
                        </a>
                    </button>
                </div>

            </main>
        </body>

        </html>
<?php

    } else {
        header('Location: gestionCategories.php');
    }
} else {
    header('Location: ../login.php');
}
