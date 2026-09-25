<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';
session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']['pseudo']);
    unset($_SESSION['user']['token']);
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header('location: ../index.php');
}
