<?php
    class Admin {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getAdminByEmail($email) {
            $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>