<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once 'ressources/php/db.php';
        $id = $_GET['id'];
        $req = $db->prepare('SELECT * FROM structure WHERE id = :id');
        $req->execute([':id' => $id]);
        $res = $req->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $structure = [
                'nom' => $res['nom'],
                'adresse' => $res['adresse'],
                'CP' => $res['codePostal'],
                'ville' => $res['ville'],
                'mail' => $res['mail'],
                'telephone' => $res['telephone'],
                'referent' => $res['referent']
            ]
?>

            <!DOCTYPE html>
            <html lang="fr">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Dashboard - Ajouter une structure</title>
                <link rel="stylesheet" href="css/gestions.css">
                <link rel="stylesheet" href="css/style.css">
            </head>

            <body>
                <?php
                require 'template/sidebar.php';
                if (isset($_GET['add'])) {
                    if ($_GET['add'] === 'failure') {
                ?>
                        <span class="err">Un compte avec ce pseudo existe déjà</span>
                <?php
                    }
                }

                ?>

                <main>
                <header>
                    <h1>Modification d'une structure</h1>
                </header>
                    <form action="traitements/updateStructure.php?id=<?= $id; ?>" method="post">
                        <input type="text" name="nom" id="nom" placeholder="Nom de la structure" value="<?= $structure['nom']; ?>" required>
                        <input type="text" name="adresse" id="adresse" placeholder="Adresse postale" value="<?= $structure['adresse']; ?>" required>
                        <input type="number" name="cp" id="cp" step="1" min="1" max="99999" placeholder="Code postal" value="<?= $structure['CP']; ?>" required>
                        <input type="text" name="ville" id="ville" placeholder="Ville" value="<?= $structure['ville']; ?>" required>
                        <input type="email" name="mail" id="mail" placeholder="Adresse mail" value="<?= $structure['mail']; ?>" required>
                        <input type="tel" name="tel" id="tel" pattern="[0-9]{10}" title="Exemple 0987654321" size="10" placeholder="Numéro de téléphone" value="<?= $structure['telephone']; ?>" required>
                        <input type="text" name="referent" id="referent" placeholder="Nom du réferent" value="<?= $structure['referent']; ?>" required>
                        <input type="hidden" name="honeypot">
                        <div class="btns">
                            <button type="submit">Modifier la structure</button>
                        </div>
                    </form>
                </main>
            </body>

            </html>
<?php
        }
    } else {
        header('location: gestionStructure.php?update=nofound');
    }
} else {
    header('location: ../login.php');
}
?>