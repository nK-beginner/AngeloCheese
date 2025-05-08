<?php
class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProducts() {
        $sql = "SELECT id, name, price FROM products";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>