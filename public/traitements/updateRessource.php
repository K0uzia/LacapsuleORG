<?php
session_start();
if (isset($_SERVER['HTTP_ORIGIN'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['user'])) {
            if (isset($_POST['honeypot']) && empty($_POST['honeypot'])) {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    require_once dirname(__DIR__, 2) . '/bootstrap.php';
                                        $sql = "SELECT * FROM ressources WHERE id=:id";
                    $query = $db->prepare($sql);
                    $query->bindValue(':id', $id, PDO::PARAM_INT);
                    $query->execute();
                    $data = $query->fetch();

                    $validExt = array('.jpg', '.jpeg', '.png', '.gif');

                    if ($_FILES['image']['error'] > 0 && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
                        header('location: ../updateResssources.php?id=' . $id . '&&update=failure');
                    }
                    if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
                        $image = $data['image'];
                    } else {
                        $fileName = $_FILES['image']['name'];
                        $fileExt = "." . strtolower(substr(strrchr($fileName, '.'), 1));

                        if (!in_array($fileExt, $validExt)) {
                            header('location: ../updateResssources.php?id=' . $id . '&&update=failure');
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
                            $image = $finalName;
                            resize_img($tempPath . "/" . $uniqueName . $fileExt, $thumPath . "/" . $uniqueName . $fileExt, 350, 100, 'auto', true);
                        }
                    }

                    $title = htmlspecialchars($_POST['title']);
                    $slug = generateSlug(strip_tags($title));
                    $subtitle = htmlspecialchars($_POST['subtitle']);
                    $content = htmlspecialchars($_POST['content']);
                    $categorie = htmlspecialchars($_POST['category']);
                    $structure = htmlspecialchars($_POST['structure']);

                    $req = $db->prepare('UPDATE ressources SET title = :title, slug = :slug, subtitle = :subtitle, content = :content, image = :image,id_categories = :categorie, id_stucture = :structure WHERE id = :id');
                    $req->execute(array(
                        'id' => $id,
                        ':title' => $title,
                        ':slug' => $slug,
                        ':subtitle' => $subtitle,
                        ':content' => $content,
                        ':image' => $image,
                        ':categorie' => $categorie,
                        ':structure' => $structure
                    ));
                    if (isset($_FILES['deroule'])) {
                        for ($i = 0; $i < count($_FILES['deroule']['name']); $i++) {
                            if (isset($_FILES['deroule']['name'][$i]) && $_FILES['deroule']['error'][$i] == 0) {
                                $path = "../uploads/pdf/";
                                $type = 'deroule';
                                move_uploaded_file($_FILES['deroule']['tmp_name'][$i], $path . '' . $_FILES['deroule']['name'][$i]);
                                $name = $_FILES['deroule']['name'][$i];
                                $stmt = $db->prepare("INSERT INTO medias(`name`,`type`,`id_ressources`) VALUES (:name,:type,:id_ressource)");

                                $stmt->execute(array(
                                    ':name' => $name,
                                    ':type' => $type,
                                    ':id_ressource' => $id
                                ));
                            }
                        }
                    }
                    if (isset($_FILES['tuto'])) {
                        for ($i = 0; $i < count($_FILES['tuto']['name']); $i++) {
                            if (isset($_FILES['tuto']['name'][$i]) && $_FILES['tuto']['error'][$i] == 0) {
                                $path = "../uploads/pdf/";
                                $type = "tuto";
                                move_uploaded_file($_FILES['tuto']['tmp_name'][$i], $path . '' . $_FILES['tuto']['name'][$i]);
                                $name = $_FILES['tuto']['name'][$i];
                                $stmt = $db->prepare("INSERT INTO medias(`name`,`type`,`id_ressources`) VALUES (:name,:type,:id_ressource)");

                                $stmt->execute(array(
                                    ':name' => $name,
                                    ':type' => $type,
                                    ':id_ressource' => $id
                                ));
                            }
                        }
                    }

                    header('location: ../myaccount.php?update=success');
                } else {
                    header('location: ../updateResssources.php?update=nofound');
                }
            } else {
                http_response_code(405);
                echo 'Requête non autorisée';
            }
        }
    }
}
