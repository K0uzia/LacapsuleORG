<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];
    $role = $_SESSION['user']['role'];
    $id = $_SESSION['user']['id'];


?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <?php $pageTitle = 'CDR - Modifier une ressource'; view('head.php'); ?>
    </head>

    <body>
        <?php
        view('header.php');
        if (isset($_GET['erreur'])) {
            switch ($_GET['erreur']) {
                case 1:
                    echo '<div class="err">Aucune image n\'a été détectée</div>';
                    break;
                case 2:
                    echo '<div class="err">Le fichier n\'est pas une image</div>';
                    break;
                case 3:
                    echo '<div class="err">PDF déroulé non renseigné</div>';
                    break;
                case 4:
                    echo '<div class="err">PDF tuto non renseigné</div>';
                    break;
                case 5:
                    echo '<div class="err">Le déroulé doit être un PDF</div>';
                    break;
                case 6:
                    echo '<div class="err">Le tuto doit être un PDF</div>';
                    break;
            }
        }
        if (isset($_GET['q'])) {
            require_once dirname(__DIR__) . '/bootstrap.php';
                        $q = strip_tags($_GET['q']);
            $req = $db->prepare('SELECT r.id, r.date, r.title, r.slug, r.subtitle, r.content, r.image, r.views, c.name AS category, c.id AS catID, u.pseudo AS auteur, s.id AS sID, s.nom AS structure
            FROM ressources r
            INNER JOIN categories c ON r.id_categories = c.id
            INNER JOIN users u ON r.id_users = u.id
            INNER JOIN stucture s ON r.id_stucture = s.id
            WHERE r.slug = :q');
            $req->execute([':q' => $q]);
            $data = $req->fetch(PDO::FETCH_ASSOC);
            if ($user == $data['auteur']) {


        ?>
                <form action="traitements/updateRessource.php?id=<?= $data['id']; ?>" method="POST" enctype="multipart/form-data">
                    <h2>Modification d'une ressource</h2>
                    <input type="text" name="title" placeholder="Titre" value="<?= $data['title']; ?>">
                    <input type="text" name="subtitle" placeholder="Sous-Titre" value="<?= $data['subtitle']; ?>">
                    <input type="text" name="content" placeholder="Contenu / description" value="<?= $data['content']; ?>">
                    <select name="category" id="category">
                        <option value="<?= $data['catID']; ?>" selected><?= $data['category']; ?></option>
                        <option value="0">------------------------</option>
                        <?php
                        require_once dirname(__DIR__) . '/bootstrap.php';
                        $cat = $db->prepare('SELECT * FROM `categories`');
                        $cat->execute();
                        while ($res = $cat->fetch()) {
                            echo '<option value="' . $res['id'] . '">' . ($res['name'] ?? $res['nom']) . '</option>';
                        }
                        $cat->closeCursor();
                        ?>
                    </select>
                    <select name="structure" id="structure">
                        <option value="<?= $data['sID']; ?>" selected><?= $data['structure']; ?></option>
                        <option value="0">------------------</option>
                        <?php
                        $stru = $db->prepare('SELECT * FROM stucture');
                        $stru->execute();
                        while ($res = $stru->fetch()) {
                            echo '<option value="' . $res['id'] . '">' . ($res['name'] ?? $res['nom']) . '</option>';
                        }
                        $stru->closeCursor();
                        ?>
                    </select>

                    <img src="uploads/img/thumbnails/<?= $data['image']; ?>" width="300" alt="">

                    <dl>
                        <label for="image">Photo de la ressource</label>
                        <input type="file" name="image" id="image" placeholder="Photo de la ressource">
                    </dl>
                    <?php
                    $reqMed = $db->prepare("SELECT * FROM medias WHERE `id_ressources` =  :id_ressources");
                    $reqMed->execute(array(':id_ressources' => $data['id']));
                    $medias = $reqMed->fetchAll(PDO::FETCH_OBJ);
                    ?>
                    <dl>
                        <p>PDF rattachés à cette ressource</p>
                        <?php foreach ($medias as $media) : ?>
                            <p><?= $media->name ?> / <?= $media->type ?></p>
                            <button class="del" data-id="<?= $media->id ?>" data-name="<?= $media->name ?>">Supprimer le PDF</button>
                        <?php endforeach; ?>
                    </dl>
                    <dl id="derouleZone">
                        <label for="deroule">Déroulé de séance (Destiné aux professionnels)</label>
                        <button class="addDeroule">
                            Ajouter un déroulé
                        </button>
                        <button class="delDeroule" style="display:none;">
                            Supprimer le dernier deroulé
                        </button>
                    </dl>

                    <dl id="tutoZone">
                        <label for="tuto">Fiche de suivi (Destiné aux bénéficiaires)</label>
                        <button class="addTuto">
                            Ajouter une fiche de suivi
                        </button>
                        <button class="delTuto" style="display:none;">
                            Supprimer la dernière fiche de suivi
                        </button>
                    </dl>
                    <input type="hidden" name="honeypot">
                    <input type="submit" value="Valider">
                </form>

        <?php

            }
        }
        ?>

        <?php
        view('footer.php');
        ?>
        <script>
            const addDeroule = document.querySelector('.addDeroule')
            const delDeroule = document.querySelector('.delDeroule')
            const addTuto = document.querySelector('.addTuto')
            const delTuto = document.querySelector('.delTuto')
            const delButtons = document.querySelectorAll('.del')

            addDeroule.addEventListener('click', (e) => {
                e.preventDefault()
                let input = document.createElement("input")
                input.type = "file"
                input.name = "deroule[]"
                input.accept = '.pdf'
                input.classList.add("derouleInput")
                document.getElementById("derouleZone").appendChild(input)
                delDeroule.style.display = "inline-block"
            })
            delDeroule.addEventListener('click', (e) => {
                e.preventDefault()
                let inputs = document.querySelectorAll('.derouleInput')
                let lastInput = inputs[inputs.length - 1]
                document.getElementById("derouleZone").removeChild(lastInput)
                if (inputs.length == 1) {
                    delDeroule.style.display = "none"
                }
            })

            addTuto.addEventListener('click', (e) => {
                e.preventDefault()
                let input = document.createElement("input")
                input.type = "file"
                input.name = "tuto[]"
                input.accept = '.pdf'
                input.classList.add("tutoInput")
                document.getElementById("tutoZone").appendChild(input)
                delTuto.style.display = "inline-block"
            })
            delTuto.addEventListener('click', (e) => {
                e.preventDefault()
                let inputs = document.querySelectorAll('.tutoInput')
                let lastInput = inputs[inputs.length - 1]
                document.getElementById("tutoZone").removeChild(lastInput)
                if (inputs.length == 1) {
                    delTuto.style.display = "none"
                }
            })

            for (let del of delButtons) {
                del.addEventListener('click', (e) => {
                    e.preventDefault()
                    if (confirm('Voulez-vous vraiment supprimer ce PDF ?')) {
                        let id = del.dataset.id
                        window.location.href = `traitements/delPDF.php?id=${id}`
                    }
                })
            }
        </script>
    </body>

    </html>
<?php
} else {
    header('Location: index.php');
}
?>