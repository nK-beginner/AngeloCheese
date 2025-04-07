<?php
    function fncGetUserByEmail($pdo, $email) {
        $stmt = $pdo -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>