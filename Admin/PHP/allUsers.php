




<?php
    session_start();
    require_once __DIR__.'/../../Main/Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    try {
        $stmt = $pdo -> prepare("SELECT * FROM test_users");
        $stmt -> execute();
        $users = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());
        $errors[] = 'データベース接続エラー';
    }

?>