<?php
    session_start();
    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/../Backend/csrf_token.php';

    $errors      = $_SESSION['errors'] ?? [];
    $firstName   = $_SESSION['old-firstName'] ?? ''; // エラー時に苗字を保持
    $lastName    = $_SESSION['old-lastName'] ?? '';  // エラー時に名前を保持
    $email       = $_SESSION['old-email'] ?? '';     // エラー時にメールを保持

    unset($_SESSION['errors'], $_SESSION['old-firstName'], $_SESSION['old-lastName']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // 受け取り
        $firstName = trim($_POST['first-name'] ?? '');
        $lastName  = trim($_POST['last-name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = trim($_POST['password'] ?? '');

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        try {
            $stmt = $pdo2 -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
            $stmt -> execute();
            $admin = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($admin) {
                $errors[] = 'このメールアドレスは既に登録されています。';
            }

            if(!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old-firstName'] = $firstName;
                $_SESSION['old-lastName'] = $lastName;
                $_SESSION['old-email'] = $email;
                header('Location: adminRegister.php');
                exit;
            }

            // パスワードハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 登録
            $stmt = $pdo2 -> prepare("INSERT INTO admin (firstName, lastName, email, password)
                                                values (:firstName, :lastName, :email, :password)");
            $stmt -> bindValue(":firstName", $firstName     , PDO::PARAM_STR);
            $stmt -> bindValue(":lastName",  $lastName      , PDO::PARAM_STR);
            $stmt -> bindValue(":email",     $email         , PDO::PARAM_STR);
            $stmt -> bindValue(":password",  $hashedPassword, PDO::PARAM_STR);
            $stmt -> execute();

            // セッション固定攻撃対策
            session_regenerate_id(true);

            header('Location: adminLogin.php');
            exit;

        } catch(PDOException $e) {
            $errors[] = 'データベース接続エラー';
            
        }

    }
?>