<?php
    require_once __DIR__ . '/../Utils/functions.php';
    fncCheckSession();
    
    require_once __DIR__.'/../Core/connection2.php';
    require_once __DIR__.'/../App/Controller/customer_controller.php';

    $controller = new CustomerController($pdo);
    $controller->listCustomers();
?>