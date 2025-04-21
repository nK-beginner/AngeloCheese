<?php
    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/../PHP/function/functions.php';
    require_once __DIR__ . '/../PHP/function/dataControl.php';

    class ProductModel {
        public static function getCategorizedProducts($pdo) {
            $products = fncGetData($pdo, 1, 1);

            $categorized = [];
            foreach ($products as $product) {
                $category = $product['category_name'];
                $categorized[$category][] = $product;
            }
            return $categorized;
        }

        public static function exportCSVIfRequested($pdo) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                exportCSV($pdo);
            }
        }
    }
?>