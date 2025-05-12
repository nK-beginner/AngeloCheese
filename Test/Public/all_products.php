<?php
    require_once __DIR__.'/../Core/connection.php';

    require_once __DIR__.'/../App/Controller/product_controller.php';
    $controller = new ProductController($pdo);
    $controller->allProducts();
?>