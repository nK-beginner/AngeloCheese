<?php
    class Product {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getAllProducts() {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProductById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getNameAndPrice() {
            $stmt = $this->pdo->prepare("SELECT id, name, tax_included_price, category_name, hidden_at FROM products");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProductMainImg($id) {
            $stmt = $this->pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main = 1");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getProductSubImgs($id) {
            $stmt = $this->pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main = NULL");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>