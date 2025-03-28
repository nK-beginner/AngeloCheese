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

    $errors = $_SESSION['errors'] ?? [];
    $email = $_SESSION['old_email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old_email']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワード入力チェック
        if(empty($password)) {
            $errors[] = 'パスワードを入力してください。';
        }

        // レートリミットチェック（ブルートフォース対策：5回以上失敗で15分ログイン阻止）
        $ip = $_SERVER['REMOTE_ADDR'];
        $failed_login_key = 'failed_login_'.$ip;
        if(isset($_SESSION[$failed_login_key]) && $_SESSION[$failed_login_key]['count'] >= 5) {
            if(time() - $_SESSION[$failed_login_key]['last_attempt'] < 900) {
                $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';

            } else {
                unset($_SESSION[$failed_login_key]);
            }
        }
        
        // エラーがなければSQL確認
        try {
            if(empty($errors)) {
                $stmt = $pdo -> prepare("SELECT id, firstName, lastName, email, password FROM test_users WHERE email = :email LIMIT 1");
                $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
                $stmt -> execute();
                $user = $stmt -> fetch(PDO::FETCH_ASSOC);

                if($user && password_verify($password, $user['password'])) {
                    // セッション固定攻撃対策
                    session_regenerate_id(true);

                    // セッション各情報へデータ格納
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['firstName'] = $user['firstName'];
                    $_SESSION['lastName']  = $user['lastName'];
                    $_SESSION['email']     = $user['email'];

                    // クッキー
                    require_once __DIR__.'/../Backend/cookie.php';

                    if(isset($_SESSION['fromCart'])) {
                        header('Location: ../View/cart.php');

                    } else {
                        header('Location: ../View/onlineShop.php');
                    }
                    exit;


                } elseif($user['deleted_at'] !== NULL) {
                    $errors[] = '存在しないユーザーか、削除されたユーザーです。';

                } else {
                    // ログイン失敗回数を記録
                    $_SESSION[$failed_login_key]['count'] = ($_SESSION[$failed_login_key]['count'] ?? 0) + 1; 

                    // 最後にログイン失敗した時刻を保存
                    $_SESSION[$failed_login_key]['last_attempt'] = time(); 
                    
                    $errors[] = 'メールアドレスまたはパスワードが間違っています。';
                }
            }

        } catch(PDOException $e) {
            error_log('データベース接続エラー：' . $e -> getMessage());
            $error[] = 'データベース接続エラー';
        }


        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_email']  = $email;

            header('Location: ../View/Login.php');
            exit;
        }
    }
?>