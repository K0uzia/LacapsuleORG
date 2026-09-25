<?php
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require_once dirname(__DIR__, 2) . '/bootstrap.php';
    $req = $db->prepare('SELECT * FROM ressources WHERE id = :id');
    $req->execute(array(":id" => $id));
    $data = $req->fetch(PDO::FETCH_ASSOC);
    $medias = $db->prepare('SELECT * FROM medias WHERE id_ressources = :id');
    $medias->execute(array(":id" => $id));
    $files = $medias->fetchAll(PDO::FETCH_OBJ);
    unlink('../uploads/img/' . $data['image']);
    if ($files) {
        foreach ($files as $file) {
            unlink('../uploads/pdf/' . $file->name);
            $fileDel = $db->prepare('DELETE FROM medias WHERE id = :id');
            $fileDel->execute(array(":id" => $file->id));
        }
    }



    $del = $db->prepare('DELETE FROM ressources WHERE id = :id');
    $del->execute(array(":id" => $id));
    header('location: ../myressources.php?del=success');
} else {
    header('location: ../myressources.php?del=failure');
}
