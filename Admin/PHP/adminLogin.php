<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/../Backend/csrf_token.php';
    require_once __DIR__ . '/../PHP/function/functions.php';

    $errors = $_SESSION['errors'] ?? [];
    $email  = $_SESSION['old-email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old-email']);

    // CSRFトークンが未セットなら生成
    // if (!isset($_SESSION['csrf_token'])) {
    //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    // }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

        // 受け取り
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // バリデーション
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        if (empty($password)) {
            $errors[] = 'パスワードを入力してください。';
        }

        // レートリミットチェック
        $ip = $_SERVER['REMOTE_ADDR'];
        $login_key = 'failed_login_' . $ip;
        if (fncManageAttempts($login_key)) {
            $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';
        }

        try {
            $admin = fncGetUserByEmail($pdo2, $email);
        } catch(PDOException $e) {
            error_log('データベースエラー:' . $e->getMessage());
            $errors[] = 'データベース接続エラー';
        }

        if (!$admin) {
            $errors[] = '存在しないメールアドレスです。';
        }

        if ($admin && !password_verify($password, $admin['password'])) {
            $errors[] = 'パスワードが間違っています。';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old-email'] = $email;
            
            header('Location: adminLogin.php');
            exit;

        } else {
            // セッション固定攻撃対策
            session_regenerate_id(true);

            // セッションに id, firstName, lastName を保存
            $_SESSION['adminId']   = $admin['id'];
            $_SESSION['adminName'] = $admin['firstName'] . ' ' . $admin['lastName']; // フルネームを保存

            header('Location: itemAdd.php');
            exit;
        }
    }
?>
