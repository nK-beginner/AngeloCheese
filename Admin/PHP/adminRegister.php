<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/../Backend/csrf_token.php';
    require_once __DIR__ . '/../PHP/function/functions.php';
    require_once __DIR__ . '/../PHP/class/adminRepository.php';

    $errors      = $_SESSION['errors'] ?? [];
    $firstName   = $_SESSION['old-firstName'] ?? '';
    $lastName    = $_SESSION['old-lastName'] ?? '';
    $email       = $_SESSION['old-email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old-firstName'], $_SESSION['old-lastName']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

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

        $adminRepo = new AdminRepository($pdo2);

        // メアド重複チェック
        try {
            $admin = $adminRepo -> getUserByEmail($email);

            if($admin) {
                $errors[] = 'このメールアドレスは既に登録されています。';
            }

        } catch(PDOException $e) {
            error_log('データベースエラー:' . $e->getMessage());
            $errors[] = 'データベース接続エラー';
        }

        if(!empty($errors)) {
            $_SESSION['errors']        = $errors;
            $_SESSION['old-firstName'] = $firstName;
            $_SESSION['old-lastName']  = $lastName;
            $_SESSION['old-email']     = $email;

            header('Location: adminRegister.php');
            exit;
        } 

        $pdo2 -> beginTransaction();
        try {
            // パスワードハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // 登録
            $adminRepo -> saveAdmin($firstName, $lastName, $email, $hashedPassword);
    
            // セッション固定攻撃対策
            session_regenerate_id(true);
    
            $pdo2 -> commit();
    
            header('Location: adminLogin.php');
            exit;
    
        } catch(PDOException $e) {
            $pdo2 -> rollBack();
            error_log("ユーザー登録エラー: " . $e -> getMessage());
            
            $_SESSION['errors']        = '登録処理中にエラーが発生しました。もう一度お試しください。';
            $_SESSION['old-firstName'] = $firstName;
            $_SESSION['old-lastName']  = $lastName;
            $_SESSION['old-email']     = $email;

            header('Location: adminRegister.php');
            exit;
        }
    }
?>