<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require_once '../ressources/php/db.php';
    $req = $db->query('SELECT * FROM ressources WHERE id_structure=' . $id);
    $req->execute();
    $result = $req->fetch();
    if (!$result) {
        $del = $db->query('DELETE FROM structure WHERE id=' . $id);
        $exec = $del->execute();
        header('location: ../gestionStructures.php?del=success');
    } else {
        header('location: ../gestionStructures.php?del=issetRessource');
    }
} else {
    header('location: ../gestionStructures.php?del=failure');
}
