<?php
require_once "../Model/Product.php";

class ProductController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Product($pdo);
    }

    public function listProducts() {
        $products = $this->model->getAllProducts();

        require '../View/product_list.php';
    }

    public function showDetail($id) {
        $product = $this->model->getProductById($id);
        
        if ($product) {
            require '../View/product_detail.php';
        } else {
            echo "商品が見つかりませんでした。";
        }
    }
}
?>