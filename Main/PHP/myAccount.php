<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(!isset($_SESSION['user_id']) || !isset($_COOKIE['remember_token'])) {

        header('Location: ../View/login.php');
        exit;
    }
?>