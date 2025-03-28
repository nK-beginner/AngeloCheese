<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])) {
        header('Location: ../View/onlineShop.php');
        exit;
    }
    
    $errors   = $_SESSION['errors'] ?? [];
    $firstName = $_SESSION['old_firstName'] ?? '';
    $lastName  = $_SESSION['old_lastName'] ?? '';
    $email    = $_SESSION['old_email'] ?? '';
    
    unset($_SESSION['errors'], $_SESSION['old_firstName'], $_SESSION['old_lastName'], $_SESSION['old_email']); // 一度表示したら削除

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        // フォームデータ取得とサニタイズ
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

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
        $stmt = $pdo -> prepare("SELECT id FROM test_users WHERE email = :email LIMIT 1");
        $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch();

        if($user) {
            $errors[] = 'このメールアドレスは既に登録されています。';
        }

        // レートリミットチェック（スパム対策）
        $ip = $_SERVER['REMOTE_ADDR'];
        $register_key = 'register_attempt_'.$ip;
        if(isset($_SESSION[$register_key]) && $_SESSION[$register_key]['count'] >= 3) {
            if(time() - $_SESSION[$register_key]['last_attempt'] < 3600) {
                $errors[] = 'アカウント登録の試行回数が多すぎます。しばらくしてから再試行してください。';
            } else {
                unset($_SESSION[$register_key]);
            }
        }

        // エラーあったら特定の情報を残しつつ、フォームへ戻る
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName'] = $lastName;
            $_SESSION['old_email'] = $email;
            header('Location: ../View/Register.php');
            exit;
        }
        
        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // ユーザー登録処理
            $stmt = $pdo -> prepare("INSERT INTO test_users (firstName, lastName, email, password)
                                                    VALUES(:firstName, :lastName, :email, :password)");
            $stmt -> bindValue(':firstName', $firstName, PDO::PARAM_STR);
            $stmt -> bindValue(':lastName',  $lastName,  PDO::PARAM_STR);
            $stmt -> bindValue(':email',     $email,     PDO::PARAM_STR);
            $stmt -> bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
            $stmt -> execute();

            // セッション固定攻撃対策
            session_regenerate_id(true);

            // 登録後に自動ログイン
            $_SESSION['user_id']   = $pdo -> lastInsertId();
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName']  = $lastName;
            $_SESSION['email']     = $email;

            // クッキー
            require_once __DIR__.'/../Backend/cookie.php';

            header('Location: ../View/OnlineShop.php');
            exit;

        } catch(PDOException $e) {
            $pdo->rollBack();

            error_log("ユーザー登録エラー: " . $e->getMessage());

            $_SESSION['errors'] = ['登録処理中にエラーが発生しました。もう一度お試しください。'];
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName'] = $lastName;
            $_SESSION['old_email'] = $email;
            header('Location: ../View/Register.php');
            exit;
        }
    }
?>