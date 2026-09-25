<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 3) {
    $id_user = htmlspecialchars($_SESSION['user']['id']);
    $pseudo = htmlspecialchars($_SESSION['user']['pseudo']);
    $token = htmlspecialchars($_SESSION['user']['token']);
    if (isset($_GET['id'])) {
        require_once 'ressources/php/db.php';
        $id = $_GET['id'];
        require_once 'ressources/php/db.php';
        $req = $db->prepare('SELECT r.id, r.date, r.title, r.subtitle, r.content, r.image, r.deroule, r.tuto, r.id_users, c.id AS catID, c.nom AS category, u.pseudo AS auteur, s.id AS sID, s.nom AS structure
            FROM ressources r
            INNER JOIN categories c ON r.id_categories = c.id
            INNER JOIN users u ON r.id_users = u.id
            INNER JOIN structure s ON r.id_structure = s.id
            WHERE r.id = :id');
        $req->execute([":id" => $id]);
        $data = $req->fetch();
        if ($data) {
            $structure = [
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'content' => $data['content'],
                'image' => $data['image'],
                'deroule' => $data['deroule'],
                'tuto' => $data['tuto'],
                'catID' => $data['catID'],
                'category' => $data['category'],
                'sID' => $data['sID'],
                'structure' => $data['structure']
            ]
?>

            <!DOCTYPE html>
            <html lang="fr">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Dashboard - Modifier une ressource</title>
                <link rel="stylesheet" href="css/style.css">
                <link rel="stylesheet" href="css/gestions.css">
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
                    <h1>Modification d'une ressource</h1>
                </header>
                    <form action="../traitements/updateRessource.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
                        <input type="text" name="title" placeholder="Titre" value="<?= $data['title']; ?>">
                        <input type="text" name="subtitle" placeholder="Sous-Titre" value="<?= $data['subtitle']; ?>">
                        <input type="text" name="content" placeholder="Contenu / description" value="<?= $data['content']; ?>">
                        <select name="category" id="category">
                            <option value="<?= $data['catID']; ?>" selected><?= $data['category']; ?></option>
                            <option value="0">------------------------</option>
                            <?php
                            require_once 'ressources/php/db.php';
                            $cat = $db->prepare('SELECT * FROM `categories`');
                            $cat->execute();
                            while ($res = $cat->fetch()) {
                                echo '<option value="' . $res['id'] . '">' . $res['nom'] . '</option>';
                            }
                            $cat->closeCursor();
                            ?>
                        </select>
                        <select name="structure" id="structure">
                            <option value="<?= $data['sID']; ?>" selected><?= $data['structure']; ?></option>
                            <option value="0">------------------</option>
                            <?php
                            $stru = $db->prepare('SELECT * FROM structure');
                            $stru->execute();
                            while ($res = $stru->fetch()) {
                                echo '<option value="' . $res['id'] . '">' . $res['nom'] . '</option>';
                            }
                            $stru->closeCursor();
                            ?>
                        </select>
                        <dl>
                            <img src="../uploads/img/thumbnails/<?= $data['image']; ?>" width="150" alt="">
                            <label for="image">Photo de la ressource</label>
                            <input type="file" name="image" id="image" placeholder="Photo de la ressource">
                        </dl>
                        <dl>
                            <label for="deroule">Déroulé de séance </label>
                            <strong>(Destiné aux professionnels)</strong>
                            <p>Déroulé actuel : <strong><?= $data['deroule']; ?></strong></p>
                            <input type="file" name="deroule" id="deroule">
                        </dl>
                        <dl>
                            <label for="tuto">Fiche de suivi </label>
                            <strong>(Destiné aux bénéficiaires)</strong>
                            <p>Fiche actuelle : <strong><?= $data['tuto']; ?></strong></p>
                            <input type="file" name="tuto" id="tuto">
                        </dl>
                            <input type="hidden" name="honeypot">
                            <button type="submit">Modifier la ressource</button>
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