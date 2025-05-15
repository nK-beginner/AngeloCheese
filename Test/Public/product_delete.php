<?php
    require_once __DIR__.'/../Utils/functions.php';
    fncCheckSession();
    
    require_once __DIR__.'/../Core/connection.php';
    require_once __DIR__.'/../App/Controller/product_controller.php';

    $controller = new ProductController($pdo);
    $controller->listProductsForDelete();
?>