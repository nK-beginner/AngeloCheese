<?php
    // ログイン状態なければログイン画面へ強制移動
    if(!isset($_SESSION['adminId'])) {
        header('Location: adminLogin.php');
        exit;
    }
?>