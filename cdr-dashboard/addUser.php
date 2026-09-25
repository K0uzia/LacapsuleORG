<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Ajouter un utilisateur</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/gestions.css">
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
                <h1>Ajout d'un utilisateur</h1>
            </header>
            <form action="traitements/addUser.php" method="post">
                <input type="name" name="nom" id="nom" placeholder="Nom" required>
                <input type="name" name="prenom" id="prenom" placeholder="Prénom" required>
                <input type="email" name="mail" id="mail" placeholder="Mail" required>
                <input type="name" name="pseudo" id="pseudo" placeholder="Pseudo" required>
                <input type="text" name="structure" id="structure" placeholder='Structure' required>
                <label for="level">Rôle de l'utilisateur</label>
                <select name="level" id="level" required>
                    <option value="2">Professionnel</option>
                    <option value="3">Administrateur</option>
                </select>
                <input type="hidden" name="honeypot">
                <div class="btns">
                    <button type="submit">Ajouter l'utilisateur</button>
                </div>
            </form>
        </main>
    </body>

    </html>
<?php
} else {
    header('location: login.php');
}
