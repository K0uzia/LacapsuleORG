<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            require_once '../ressources/php/db.php';

            $nom = htmlspecialchars($_POST['nom']);
            $adresse = htmlspecialchars($_POST['adresse']);
            $cp = htmlspecialchars($_POST['cp']);
            $ville = htmlspecialchars($_POST['ville']);
            $mail = htmlspecialchars($_POST['mail']);
            $tel = htmlspecialchars($_POST['tel']);
            $referent = htmlspecialchars($_POST['referent']);

            $req = $db->prepare('INSERT INTO structure (nom, adresse, codePostal, ville, mail, telephone, referent) VALUES (:nom, :adresse, :cp, :ville, :mail, :tel, :referent)');
            $req->execute(array(
                'nom' => $nom,
                'adresse' => $adresse,
                'cp' => $cp,
                'ville' => $ville,
                'mail' => $mail,
                'tel' => $tel,
                'referent' => $referent
            ));
            header('location: ../gestionStructures.php?add=success');
        }
    }
}
