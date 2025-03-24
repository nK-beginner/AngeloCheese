




<?php
    // CSRFトークン生成
    if(empty($_SESSION['hidden'])) {
        $_SESSION['hidden'] = bin2hex(random_bytes(32)); // CSRFトークン存在しなければ生成
    }

    // CSRF
    function fncVerifyToken($token) {
        if (!isset($_SESSION['hidden']) || $token !== $_SESSION['hidden']) {
            die('CSRFトークン不一致エラー');
        }

        unset($_SESSION['hidden']);
        $_SESSION['hidden'] = bin2hex(random_bytes(32));
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