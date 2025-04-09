<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../../Main/Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    try {
        $users = fncGetData($pdo, 3, 1);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());
        $errors[] = 'データベース接続エラー';
    }

    if(!empty($errors)) {
        $_SESSION['errors'] = $errors;

        header("Location: allUsers.php");
        exit;
    }
?>