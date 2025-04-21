<?php
    require_once __DIR__.'/../../Main/Backend/connection.php';
    require_once __DIR__ . '/../PHP/function/functions.php';
    require_once __DIR__ . '/../PHP/function/dataControl.php';

    function getAllUsers(PDO $pdo): array {
        return fncGetData($pdo, 3, 1);
    }
?>