<?php
    class Customer {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getAllCustomers() {
            $stmt = $this->pdo->prepare("SELECT * FROM test_users");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>