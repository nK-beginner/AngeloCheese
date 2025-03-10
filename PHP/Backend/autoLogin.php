




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../Backend/connection.php';

    if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
        $stmt = $pdo -> prepare("SELECT * FROM test_users WHERE remember_token = :token LIMIT 1");
        $stmt -> bindValue(':token', $_COOKIE['remember_token'], PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // セッション固定攻撃対策
            session_regenerate_id(true);

            // セッションにログイン情報をセット
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName']  = $user['lastName'];
            $_SESSION['email']     = $user['email'];
        }
    }
?>
