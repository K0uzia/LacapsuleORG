<?php
session_start();
if (isset($_SESSION['user'])) {
    $id_user = $_SESSION['user']['id'];
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
                if (!preg_match('!^ *$!s', $_POST['title']) && !preg_match('!^ *$!s', $_POST['subtitle']) && !preg_match('!^ *$!s', $_POST['content'])) {
                    require_once dirname(__DIR__, 2) . '/bootstrap.php';
                    
                    $validExt = array('.jpg', '.jpeg', '.png', '.gif');

                    if ($_FILES['image']['error'] > 0) {
                        header('location: ../postresssources.php?erreur=1');
                    }
                    $fileName = $_FILES['image']['name'];
                    $fileExt = "." . strtolower(substr(strrchr($fileName, '.'), 1));

                    if (!in_array($fileExt, $validExt)) {
                        header('location: ../postressources.php?erreur=2');
                    } else {
                        $tmpName = $_FILES['image']['tmp_name'];
                        $uniqueName = md5(uniqid(rand(), true));
                        $tempPath = "../uploads/img/temp";
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0777);
                            $fileName = $tempPath . "/" . $uniqueName . $fileExt;
                        } else {
                            $fileName = $tempPath . "/" . $uniqueName . $fileExt;
                        }

                        $finalName = $uniqueName . $fileExt;
                        $result = move_uploaded_file($tmpName, $fileName);
                        $path = "../uploads/img";
                        if (!file_exists($path)) {
                            mkdir($path, 0777);
                        }
                        resize_img($tempPath . "/" . $uniqueName . $fileExt, $path . "/" . $uniqueName . $fileExt, 720, 100, 'auto', true);
                        $thumPath = "../uploads/img/thumbnails";
                        if (!file_exists($thumPath)) {
                            mkdir($thumPath, 0777);
                        }
                        resize_img($tempPath . "/" . $uniqueName . $fileExt, $thumPath . "/" . $uniqueName . $fileExt, 350, 100, 'auto', true);
                        unlink($tempPath . "/" . $uniqueName . $fileExt);


                        $title = htmlspecialchars($_POST['title']);
                        $slug = generateSlug($title);
                        $subtitle = htmlspecialchars($_POST['subtitle']);
                        $content = htmlspecialchars($_POST['content']);
                        $categorie = htmlspecialchars($_POST['category']);
                        $structure = htmlspecialchars($_POST['structure']);
                        $image = $finalName;
                        $date = date('Y-m-d H:i:s');

                        $req = $db->prepare('INSERT INTO ressources (title, slug,subtitle, content, image, date, id_categories, id_stucture, id_users) VALUES (:title, :slug,:subtitle, :content, :image, :date, :categorie, :structure, :user)');
                        $req->execute(array(
                            ':title' => $title,
                            ':slug' => $slug,
                            ':subtitle' => $subtitle,
                            ':content' => $content,
                            ':image' => $image,
                            ':date' => $date,
                            ':categorie' => $categorie,
                            ':structure' => $structure,
                            ':user' => $_SESSION['user']['id']
                        ));

                        $select = $db->query("SELECT * FROM ressources ORDER BY id DESC LIMIT 1");
                        $last = $select->fetch(PDO::FETCH_OBJ);


                        if (isset($_FILES['deroule'])) {
                            for ($i = 0; $i < count($_FILES['deroule']['name']); $i++) {
                                if (isset($_FILES['deroule']['name'][$i]) && $_FILES['deroule']['error'][$i] == 0) {
                                    $path = "../uploads/pdf/";
                                    $type = 'deroule';
                                    $id_ressource = $last->id;
                                    move_uploaded_file($_FILES['deroule']['tmp_name'][$i], $path . '' . $_FILES['deroule']['name'][$i]);
                                    $name = $_FILES['deroule']['name'][$i];
                                    $stmt = $db->prepare("INSERT INTO medias(`name`,`type`,`id_ressources`) VALUES (:name,:type,:id_ressource)");

                                    $stmt->execute(array(
                                        ':name' => $name,
                                        ':type' => $type,
                                        ':id_ressource' => $id_ressource
                                    ));
                                }
                            }
                        }
                        if (isset($_FILES['tuto'])) {
                            for ($i = 0; $i < count($_FILES['tuto']['name']); $i++) {
                                if (isset($_FILES['tuto']['name'][$i]) && $_FILES['tuto']['error'][$i] == 0) {
                                    $path = "../uploads/pdf/";
                                    $type = "tuto";
                                    $id_ressource = $last->id;
                                    move_uploaded_file($_FILES['tuto']['tmp_name'][$i], $path . '' . $_FILES['tuto']['name'][$i]);
                                    $name = $_FILES['tuto']['name'][$i];
                                    $stmt = $db->prepare("INSERT INTO medias(`name`,`type`,`id_ressources`) VALUES (:name,:type,:id_ressource)");

                                    $stmt->execute(array(
                                        ':name' => $name,
                                        ':type' => $type,
                                        ':id_ressource' => $id_ressource
                                    ));
                                }
                            }
                        }

                        header('Location: ../postressources.php?add=success');
                    }
                } else {
                    header('location: ../postressources.php?add=empty');
                }
            } else {
                http_response_code(405);
                echo 'Requête non autorisée !';
            }
        }
    } else {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}
