<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../backend/connection.php';
    require_once __DIR__ . '/../backend/csrf_token.php';
    require_once __DIR__ .'/Common/class.php';

    if (isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])) {
        header('Location: ../View/onlineShop.php');
        exit;
    }

    $errors    = $_SESSION['errors'] ?? [];
    $firstName = $_SESSION['old_firstName'] ?? '';
    $lastName  = $_SESSION['old_lastName'] ?? '';
    $email     = $_SESSION['old_email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old_firstName'], $_SESSION['old_lastName'], $_SESSION['old_email']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../backend/check.php';

        // フォームデータ取得とサニタイズ
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        // Accountクラスのインスタンス化とデータセット
        $account = new Account($pdo);
        $account -> setInputData($firstName, $lastName, $email, $password);

        // バリデーションチェック
        if (!$account -> validate()) {
            $_SESSION['errors']        = $account -> getErrors();
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName']  = $lastName;
            $_SESSION['old_email']     = $email;

            header('Location: ../View/Register.php');
            exit;
        }

        // 登録処理
        try {
            $userId = $account -> register();

            // セッション固定攻撃対策
            session_regenerate_id(true);

            // 登録後に自動ログイン
            $_SESSION['user_id']   = $userId;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName']  = $lastName;
            $_SESSION['email']     = $email;

            // クッキー
            require_once __DIR__ . '/../Backend/cookie.php';

            header('Location: ../View/OnlineShop.php');
            exit;

        } catch (Exception $e) {
            error_log($e -> getMessage());

            $_SESSION['errors']        = ['登録処理中にエラーが発生しました。もう一度お試しください。'];
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName']  = $lastName;
            $_SESSION['old_email']     = $email;

            header('Location: ../View/Register.php');
            exit;
        }
    }
?>
