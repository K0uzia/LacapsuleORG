<?php
session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']['pseudo']);
    unset($_SESSION['user']['token']);
    unset($_SESSION['user']['role']);
    unset($_SESSION['user']['id']);
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header('location: ../index.php');
}
