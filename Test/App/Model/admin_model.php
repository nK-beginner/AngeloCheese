<?php
    class Admin {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function beginTransaction() {
            $this->pdo->beginTransaction();
        }

        public function commit() {
            $this->pdo->commit();
        }

        public function rollBack() {
            $this->pdo->rollBack();
        }

        public function getByEmail($email) {
            $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function saveAdmin($firstName, $lastName, $email, $hashedPassword) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO admin (firstName, lastName, email, password)
                 VALUES (:firstName, :lastName, :email, :password)");
            $stmt->bindValue(":firstName", $firstName     , PDO::PARAM_STR);
            $stmt->bindValue(":lastName",  $lastName      , PDO::PARAM_STR);
            $stmt->bindValue(":email",     $email         , PDO::PARAM_STR);
            $stmt->bindValue(":password",  $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
?>