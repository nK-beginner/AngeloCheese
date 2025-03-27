



<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // クッキー設定
    $token = bin2hex(random_bytes(32));
    $expire = time() + (7 * 24 * 60 * 60);
    setcookie('remember_token', $token, $expire, '/', '', false, true); // ※公開時はfalse, trueにすること：HttpOnlyに
    // クッキー用のトークンを生成
    $stmt = $pdo -> prepare("UPDATE test_users SET remember_token = :token WHERE id = :id");
    $stmt -> bindValue(':token', $token,      PDO::PARAM_STR);
    $stmt -> bindValue(':id',    $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt -> execute();
?>