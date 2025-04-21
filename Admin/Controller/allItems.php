<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../Model/allItems.php';

    $categorizedProducts = ProductModel::getCategorizedProducts($pdo2);
    ProductModel::exportCSVIfRequested($pdo2);

    // Viewを呼び出す
    require_once __DIR__ . '/../View/allItems.php';
?>