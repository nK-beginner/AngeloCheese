<?php
    fncSessionCheck();

    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/../Backend/csrf_token.php';
    require_once __DIR__ . '/../PHP/function/functions.php';

    $errors      = $_SESSION['errors'] ?? [];
    $firstName   = $_SESSION['old-firstName'] ?? '';
    $lastName    = $_SESSION['old-lastName'] ?? '';
    $email       = $_SESSION['old-email'] ?? '';

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

        // メアド重複チェック
        if(fncGetUserByEmail($pdo2, $email)) {
            $errors[] = 'このメールアドレスは既に登録されています。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old-firstName'] = $firstName;
            $_SESSION['old-lastName'] = $lastName;
            $_SESSION['old-email'] = $email;

            header('Location: adminRegister.php');
            exit;
        }

        $pdo2 -> beginTransaction();
        try {
            // パスワードハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 登録
            fncSaveUser($pdo2, $firstName, $lastName, $email, $hashedPassword);

            // セッション固定攻撃対策
            session_regenerate_id(true);

            $pdo2 -> commit();

            header('Location: adminLogin.php');
            exit;

        } catch(PDOException $e) {
            $pdo2 -> rollBack();
            error_log("ユーザー登録エラー: " . $e -> getMessage());
            
            $_SESSION['errors'] = '登録処理中にエラーが発生しました。もう一度お試しください。';
            
        }

    }
?>