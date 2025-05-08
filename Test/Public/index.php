<?php
$host     = 'localhost';
$dbname   = 'angelo_cheese_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password);
} catch(PDOException $e) {
    die("データベース接続エラー：".htmlspecialchars($e -> getMessage(), ENT_QUOTES, 'UTF-8'));
}

require_once '../Controller/product_controller.php';
$controller = new ProductController($pdo);

if (isset($_GET['action']) && $_GET['action'] === 'detail' && isset($_GET['id'])) {
    $controller->showDetail((int)$_GET['id']);
} else {
    $controller->listProducts();
}
?>