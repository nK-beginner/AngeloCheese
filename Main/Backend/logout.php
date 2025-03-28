<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // セッション変数クリア
    $_SESSION = [];
    session_unset();

    // セッション破棄
    session_destroy();

    // セッションクッキーを削除
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // remember meクッキー削除
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);

    // ログインページへリダイレクト
    header("Location: ../View/login.php");
    exit;
?>