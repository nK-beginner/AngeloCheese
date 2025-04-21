<?php
    class ProductModel {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        // 商品一覧取得（区分2、状態1 = 表示中）
        public function getAllVisibleProducts() {
            return fncGetData($this->pdo, 2, 1);
        }

        // 商品を非表示にする（論理削除）
        public function hideProducts(array $itemIds) {
            fncHideProducts($this->pdo, $itemIds);
        }
    }
?>