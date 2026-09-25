<?php
session_start();
if (isset($_SESSION['user'])) {
    require_once dirname(__DIR__, 2) . '/bootstrap.php';
    $id = $_GET['id'];
    $req = $db->prepare("SELECT * FROM medias WHERE id = :id");
    $req->execute(array(":id" => $id));
    $data = $req->fetch(PDO::FETCH_OBJ);
    if ($data) {
        $del = $db->prepare("DELETE FROM medias WHERE id = :id");
        $del->execute(array(":id" => $id));
        unlink("../uploads/pdf/$data->name");
        header('Location: ' . $_SERVER["HTTP_REFERER"]);
    }
} else {
    header("location: /");
}
