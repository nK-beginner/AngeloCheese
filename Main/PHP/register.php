<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
    require_once __DIR__.'/function/functions.php';

    fncCheckStatus();
    
    $errors    = $_SESSION['errors'] ?? [];
    $firstName = $_SESSION['old_firstName'] ?? '';
    $lastName  = $_SESSION['old_lastName'] ?? '';
    $email     = $_SESSION['old_email'] ?? '';
    
    unset($_SESSION['errors'], $_SESSION['old_firstName'], $_SESSION['old_lastName'], $_SESSION['old_email']); // 一度表示したら削除

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        // フォームデータ取得とサニタイズ
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        $errors = []; // 入力エラー用配列

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        // メアド重複チェック
        if(fncGetUserByEmail($pdo, $email)) {
            $errors[] = 'このメールアドレスは既に登録されています。';
        }

        // レートリミットチェック（スパム対策）
        $register_key = 'register_attempt_' . $ip;
        if (fncManageAttempts($register_key, 3, 3600)) {
            $errors[] = 'アカウント登録の試行回数が多すぎます。しばらくしてから再試行してください。';
        }

        // エラーあれば特定の情報を残しつつ、フォームへ戻る
        if(!empty($errors)) {
            $_SESSION['errors']        = $errors;
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName']  = $lastName;
            $_SESSION['old_email']     = $email;

            header('Location: ../View/Register.php');
            exit;
        }
        
        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $pdo -> beginTransaction();
        try {
            // ユーザー登録処理
            fncSaveUser($pdo, $firstName, $lastName, $email, $hashedPassword);

            // 登録後に自動ログイン
            fncSaveToSession(['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email], 
                               $pdo -> lastInsertId()
                            );

            // クッキー
            require_once __DIR__.'/../Backend/cookie.php';

            $pdo -> commit();

            header('Location: ../View/OnlineShop.php');
            exit;

        } catch(PDOException $e) {
            $pdo -> rollBack();

            error_log("ユーザー登録エラー: " . $e -> getMessage());
            fncManageAttempts($register_key, 3, 3600, true);

            $_SESSION['errors']        = '登録処理中にエラーが発生しました。もう一度お試しください。';
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName']  = $lastName;
            $_SESSION['old_email']     = $email;

            header('Location: ../View/Register.php');
            exit;
        }
    }
?>