<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Gestion categories</title>
        <link rel="stylesheet" href="css/gestions.css">
    </head>

    <body>
        <?php
        require 'template/sidebar.php';
        if (isset($_GET)) {
            switch (key($_GET)) {
                case 'add':
                    switch ($_GET['add']) {
                        case 'success':
                            echo '<p class="success">Catégorie ajoutée avec succès !</p>';
                            break;
                        case 'failure':
                            echo '<p class="failed">Une erreur est survenue lors de l\'ajout de la catégorie.</p>';
                            break;
                    }
                    break;
                case 'update':
                    switch ($_GET['update']) {
                        case 'success':
                            echo '<p class="success">Catégorie mise à jour</p>';
                            break;
                        case 'failure':
                            echo '<p class="err">Une erreur est survenue</p>';
                            break;
                        case 'nofound':
                            echo '<p class="err">Catégorie introuvable</p>';
                            break;
                    }
                    break;
                case 'del':
                    switch ($_GET['del']) {
                        case 'cancel':
                            echo '<p class="cancel">Suppression annulée</p>';
                            break;
                        case 'success':
                            echo '<p class="success">Catégorie supprimée</p>';
                            break;
                        case 'failure':
                            echo '<p class="err">Une erreur est survenue</p>';
                            break;
                        case 'nofound':
                            echo '<p class="err">Catégorie introuvable</p>';
                            break;
                    }
                    break;
            }
        }

        ?>

        <main>
        <header>
            <h1>Gestion des catégories</h1>
        </header>
                <a class="btn" href="addCategorie.php">Ajouter une catégorie</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>nom</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'ressources/php/db.php';
                    $req = $db->query('SELECT * FROM categories ORDER BY id');
                    while ($res = $req->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td><?= $res['id']; ?></td>
                            <td><?= $res['nom']; ?></td>
                            <td>
                                <a href="viewCategorie.php?id=<?= $res['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                                    </svg></a>
                                <a href="updateCategorie.php?id=<?= $res['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="m16 2.012 3 3L16.713 7.3l-3-3zM4 14v3h3l8.299-8.287-3-3zm0 6h16v2H4z"></path>
                                    </svg></a>
                                <a href="delCategorie.php?id=<?= $res['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                        <path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm10.618-3L15 2H9L7.382 4H3v2h18V4z"></path>
                                    </svg></a>
                            </td>
                        </tr>
                    <?php
                    }
                    $req->closeCursor();
                    ?>
                </tbody>
            </table>
        </main>
    </body>

    </html>

<?php
} else {
    header('Location: login.php');
}
?>