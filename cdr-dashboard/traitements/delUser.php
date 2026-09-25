<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require_once '../ressources/php/db.php';
    $del = $db->query('DELETE FROM users WHERE id=' . $id);
    $exec = $del->execute();
    header('location: ../gestionUsers.php?del=success');
} else {
    header('location: ../gestionUsers.php?del=failure');
}
