<?php
    require_once __DIR__.'/../Core/connection.php';

    require_once __DIR__.'/../App/Controller/product_controller.php';
    $controller = new ProductController($pdo);

    if (isset($_GET['action']) && $_GET['action'] === 'detail' && isset($_GET['id'])) {
        $controller->showDetail((int)$_GET['id']);
    } else {
        $controller->listProducts();
    }
?>