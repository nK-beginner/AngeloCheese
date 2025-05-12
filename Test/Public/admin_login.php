<?php
    require_once __DIR__.'/../Core/connection.php';

    require_once __DIR__.'/../App/Controller/admin_controller.php';
    $controller = new AdminLoginController($pdo);
    $controller->login();
?>