<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            $nom = $_POST['nom'];
            $id = $_GET['id'];
            require_once '../ressources/php/db.php';
            $req = $db->prepare('SELECT * FROM structure WHERE nom = :nom');
            $req->execute([':nom' => $nom]);
            $res = $req->fetch(PDO::FETCH_ASSOC);

            $adresse = htmlspecialchars($_POST['adresse']);
            $cp = htmlspecialchars($_POST['cp']);
            $ville = htmlspecialchars($_POST['ville']);
            $mail = htmlspecialchars($_POST['mail']);
            $telephone = htmlspecialchars($_POST['tel']);
            $referent = htmlspecialchars($_POST['referent']);
            $req = $db->prepare('UPDATE structure
                                SET 
                                    nom = :nom,
                                    adresse = :adresse,
                                    codePostal = :cp,
                                    ville = :ville,
                                    mail = :mail,
                                    telephone = :tel,
                                    referent = :referent
                                WHERE id = :id');
            $req->execute([
                ':nom' => $nom,
                ':id' => $id,
                ':adresse' => $adresse,
                ':cp' => $cp,
                ':ville' => $ville,
                ':mail' => $mail,
                ':tel' => $telephone,
                ':referent' => $referent
            ]);
            header('Location: ../gestionStructures.php?update=success');
        } else {
            http_response_code(405);
            echo 'Requête non autorisée';
        }
    }
}
