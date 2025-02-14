




<?php
    // CSRFトークン生成
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // CSRFトークン存在しなければ生成
    }

    // CSRFトークンをHttpOnly & Secureクッキーに保存
    // setcookie('csrf_token', $_SESSION['csrf_token'], [
    //     'expires'   => 0,       // セッションと同じ寿命
    //     'path'      => '/',     // サイト全体で有効
    //     'secure'    => false,   // HTTPSでのみ送信(ローカル開発時は false に)
    //     'httponly'  => true,    // JSからのアクセス✖
    //     'samesite'  => 'Strict' // 対クロスサイトリクエスト✖
    // ]);
?>