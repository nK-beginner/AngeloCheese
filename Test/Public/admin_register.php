<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['adminId'])) {
        header('Location: all_products.php');
        exit;
    }

    require_once __DIR__ . '/../Core/connection.php';
    require_once __DIR__ . '/../App/Controller/admin_controller.php';

    $controller = new AdminController($pdo);
    $controller->handleRegisterRequest();
?>