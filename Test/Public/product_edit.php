<?php
    require_once __DIR__ . '/../Utils/functions.php';
    fncCheckSession();
    
    require_once __DIR__.'/../Core/connection.php';
    require_once __DIR__.'/../App/Controller/product_controller.php';

    $controller = new ProductController($pdo);

    if($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
        $controller->listProducts();
    } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->editProduct();
    }
 
    if(isset($_GET['id'])) {
        $productId = (int)$_GET['id'];
        $controller->showDetail($productId);
    }
?>