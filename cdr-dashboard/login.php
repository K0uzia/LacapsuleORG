<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cdr dashboard - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/form.css">
</head>

<body>
    <main>
        <header>
    <h1>Connexion au dashboard</h1>
    </header>
        <?php
        if (isset($_GET)) {
            switch ($_GET) {
                case 'connect':
                    switch ($_GET['connect']) {
                        case 'noadmin':
                            echo '<p class="err">Vous devez être administrateur pour accéder à cette page !</p>';
                            break;
                        case 'failure':
                            echo '<p class="err">Identifiant et/ou mot de passe incorrect !</p>';
                            break;
                    }
            }
        }

        ?>

        <form action="traitements/connect.php" method="post">
            <input type="email" name="mail" id="mail" placeholder="Mail" required>
            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
            <input type="hidden" name="honeypot">
            <div class="btns">
                <button type="submit">Ajouter l'utilisateur</button>
            </div>
        </form>
    </main>
</body>

</html>