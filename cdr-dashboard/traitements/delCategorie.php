<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require_once '../ressources/php/db.php';
    $req = $db->query('SELECT * FROM ressources WHERE id_categories=' . $id);
    $req->execute();
    $result = $req->fetch();
    if (!$result) {
        $del = $db->query('DELETE FROM categories WHERE id=' . $id);
        $exec = $del->execute();
        header('location: ../gestionCategories.php?del=success');
    } else {
        header('location: ../gestionCategories.php?del=issetRessource');
    }
} else {
    header('location: ../gestionCategories.php?del=failure');
}
