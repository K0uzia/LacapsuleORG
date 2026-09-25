<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
            require '../ressources/php/db.php';

            $userNom = htmlspecialchars($_POST['nom']);
            $userPrenom = htmlspecialchars($_POST['prenom']);
            $userMail = htmlspecialchars($_POST['mail']);
            $userPseudo = htmlspecialchars($_POST['pseudo']);
            $userStructure = htmlspecialchars($_POST['structure']);
            //Vérification si déjà en base
            $sql = 'SELECT COUNT(*) FROM users WHERE mail=:mail';
            $query = $db->prepare($sql);
            $query->bindValue(':mail', $userMail, PDO::PARAM_STR);
            $query->execute();
            $check = $query->fetch();

            if ($check['COUNT(*)'] === 0) {
                $sql = 'SELECT COUNT(*) FROM users WHERE pseudo=:pseudo';
                $query = $db->prepare($sql);
                $query->bindValue(':pseudo', $userPseudo, PDO::PARAM_STR);
                $query->execute();
                $check = $query->fetch();
                if ($check['COUNT(*)'] === 0) {
                    $userLevel = htmlspecialchars($_POST['level']);
                    $bytes = openssl_random_pseudo_bytes(16);
                    $userPassClear = bin2hex($bytes);
                    $userPassEncrypt = hash('sha512', $userPassClear);
                    $photo = 'default.png';
                    $recovery = 'null';
                    $token = hash('sha512', uniqid(rand(), true));
                    $statement = 3;

                    $request = 'INSERT INTO users(dateInscription,nom,prenom,pseudo,mail,structure,password,photo,recovery,token,id_level,id_statement)
                                            VALUES(NOW(),?,?,?,?,?,?,?,?,?,?,?)';
                    $insert = $db->prepare($request);
                    $exec = $insert->execute(array($userNom, $userPrenom, $userPseudo, $userMail, $userStructure, $userPassEncrypt, $photo, $recovery, $token, $userLevel, $statement));


                    $to = $userMail;
                    $from = 'ne-pas-repondre@lacapsule.org';
                    $subject = 'Inscription au centre de ressource';

                    $message = "<p>Bonjour $userPseudo,</p>
                                <p>Vous avez été ajouté au centre de ressource de la capsule</p>
                                <p>Pour vous connecter vous devez utilisez l'adresse mail : </p>
                                <p>$userMail</p>
                                <p>Et le mot de passe suivant : </p>
                                <p>$userPassClear</p>

                                <p>Pour finaliser votre inscription vous devez vous connecter et modifier votre mot de passe en suivant ce lien</p>
                                  <a href='https://cpam.lacapsule.org/myaccount.php'>https://cpam.lacapsule.org/myaccount.php<a>  
                                <p>A bientôt sur le site du centre de ressource</p>
                                  ";

                    $headers = "from : ne-pas-repondre@lacapsule.org" . "\r\n" .
                        'X-Mailer : PHP/' . phpversion() . "\r\n" .
                        "Content-Type: text/html; chartset=UTF-8";

                    mail($to, $subject, $message, $headers);
                    header('location: ../gestionUsers.php?add=success');
                } else {
                    header('location: ../addUser.php?add=pseudoexist');
                }
            } else {
                header('location: ../addUser.php?add=mailexist');
            }
        } else {
            http_response_code(405);
            echo 'Requête non autorisée !';
        }
    } else {
    }
}
