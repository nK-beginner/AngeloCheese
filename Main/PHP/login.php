<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/function/functions.php';

    fncCheckStatus();

    $errors = $_SESSION['errors'] ?? [];
    $email = $_SESSION['old_email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old_email']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        // メアドチェック
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワード入力チェック
        if (empty($password)) {
            $errors[] = 'パスワードを入力してください。';
        }

        // レートリミットチェック
        $ip = $_SERVER['REMOTE_ADDR'];
        $login_key = 'failed_login_' . $ip;
        if (fncManageAttempts($login_key)) {
            $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';
        }

        // エラーがなければSQL確認
        try {
            if (empty($errors)) {
                $user = fncGetUserByEmail($pdo, $email);

                if ($user && password_verify($password, $user['password'])) {
                    if ($user['deleted_at'] !== NULL) {
                        $errors[] = '存在しないユーザーか、削除されたユーザーです。';

                    } else {
                        fncSaveToSession($user);

                        // クッキー
                        require_once __DIR__.'/../Backend/cookie.php';

                        if (isset($_SESSION['fromCart'])) {
                            header('Location: ../View/cart.php');

                        } else {
                            header('Location: ../View/onlineShop.php');
                        }
                        exit;
                    }
                } else {
                    // ログイン失敗回数を記録
                    fncManageAttempts($login_key, isFailed: true);

                    $errors[] = 'メールアドレスまたはパスワードが間違っています。';
                }
            }

        } catch (PDOException $e) {
            error_log('データベース接続エラー：' . $e -> getMessage());
            $errors[] = 'データベース接続エラー';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_email'] = $email;

            header('Location: ../View/Login.php');
            exit;
        }
    }
?>
