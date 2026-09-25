<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']['pseudo'];
    $token = $_SESSION['user']['token'];

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <?php $pageTitle = 'CDR - Poster une ressource'; view('head.php'); ?>
    </head>

    <body>
        <?php
        view('header.php');
        if (isset($_GET)) {
            switch (key($_GET)) {
                case 'add':
                    switch ($_GET['add']) {
                        case 'success':
                            echo '<p class="success">Ressource ajoutée avec succès !</p>';
                            break;
                        case 'empty':
                            echo '<p class="err">L\'un des champs est vide !</p>';
                            break;
                    }
                    break;
            }
        }


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
        ?>
        <form action="traitements/postressources.php" method="POST" enctype="multipart/form-data">
            <h2>Poster une ressource</h2>
            <input type="text" name="title" placeholder="Titre" required>
            <input type="text" name="subtitle" placeholder="Sous-Titre" required>
            <input type="text" name="content" placeholder="Contenu / description" required>
            <select name="structure" id="structure" required>
                <option value="0">Selectionner une structure</option>
                <?php
                require_once dirname(__DIR__) . '/bootstrap.php';
                $req = $db->prepare('SELECT * FROM `structure`');
                $req->execute();
                while ($data = $req->fetch()) {
                    echo '<option value="' . $data['id'] . '">' . ($data['name'] ?? $data['nom']) . '</option>';
                }
                $req->closeCursor();
                ?>
            </select>
            <select name="category" id="category" required>
                <option value="0">Selectionner une catégorie</option>
                <?php
                $req = $db->prepare('SELECT * FROM categories');
                $req->execute();
                while ($data = $req->fetch()) {
                    echo '<option value="' . $data['id'] . '">' . ($data['name'] ?? $data['nom']) . '</option>';
                }
                $req->closeCursor();
                ?>
            </select>
            <dl>
                <label for="image">Photo de la ressource</label>
                <input type="file" name="image" id="image" placeholder="Photo de la ressource" required>
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
            <input type="submit">
        </form>
        <script>
            const addDeroule = document.querySelector('.addDeroule')
            const delDeroule = document.querySelector('.delDeroule')
            const addTuto = document.querySelector('.addTuto')
            const delTuto = document.querySelector('.delTuto')

            addDeroule.addEventListener('click', (e) => {
                e.preventDefault()
                let input = document.createElement("input")
                input.type = "file"
                input.name = "deroule[]"
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
        </script>
        <?php
        view('footer.php');
        ?>
    </body>

    </html>
<?php
} else {
    header('Location: index.php');
}
?>