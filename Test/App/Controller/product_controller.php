<?php
    require_once __DIR__.'/../Model/product_model.php';

    class ProductController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Product($pdo);
        }

        public function listProducts() {
            $products = $this->model->getAllProducts();

            foreach($products as &$product) {
                $mainImg = $this->model->getProductMainImg($product['id']);
                $product['main_img'] = $mainImg['image_path'] ?? null;
            }
            unset($product); // 参照渡しの解放：これがないと同じ商品が重複して表示されるので消さないこと

            require '../App/View/product_editList_view.php';
        }

        public function allProducts() {
            $products = $this->model->getNameAndPrice();

            $categorizedProducts = [];
            foreach($products as &$product) {
                $mainImg = $this->model->getProductMainImg($product['id']);
                $product['main_img'] = $mainImg['image_path'] ?? null;

                $category = $product['category_name'] ?? '未分類';
                $categorizedProducts[$category][] = $product;
            }
            unset($product); // 参照渡しの解放

            require '../App/View/all_products_view.php';
        }

        public function showDetail($id) {
            $product = $this->model->getProductById($id);
            
            if ($product) {
                require '../App/View/product_detail.php';
            } else {
                echo "商品が見つかりませんでした。";
            }
        }
    }
?>