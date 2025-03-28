<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../View/onlineShop.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        // 退会理由受け取り
        $_SESSION['reason'] = $_POST['reason'] ?? '';

        if($_SESSION['reason'] == 99) {
            $_SESSION['reasonDetail'] = $_POST['reasonDetail'] ?? '';

        } else {
            $_SESSION['reasonDetail'] = NULL;
        }

        header('Location: ../View/confirmUnsubscribe.php');
        exit;
    }
?>