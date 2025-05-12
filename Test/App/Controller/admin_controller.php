<?php
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../Model/admin_model.php';
    require_once __DIR__ . '/../../Utils/csrf_token.php';
    require_once __DIR__ . '/../../Utils/functions.php';

    class AdminLoginController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Admin($pdo);
        }

        public function login() {
            $errors = $_SESSION['errors'] ?? [];
            $email  = $_SESSION['old-email'] ?? '';
        
            unset($_SESSION['errors'], $_SESSION['old-email']);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                // require __DIR__ . '/../View/admin_login_view.php';
                require __DIR__ . '/../../Public/admin_login.php';
                exit;
                
            } else {
                // fncCheckCSRF();

                $email    = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = '有効なメールアドレスを入力してください。';
                }
        
                if (empty($password)) {
                    $errors[] = 'パスワードを入力してください。';
                }

                $ip = $_SERVER['REMOTE_ADDR'];
                $login_key = 'failed_login_' . $ip;
                if (fncManageAttempts($login_key)) {
                    $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';
                }

                try {
                    $admin = $this->model->getAdminByEmail($email);

                } catch(PDOException $e) {
                    error_log('データベースエラー:' . $e->getMessage());
                    $errors[] = 'データベース接続エラー';
                }

                if (!$admin || !password_verify($password, $admin['password'])) {
                    $errors[] = 'メールアドレスか、パスワードが間違っています。';
                }
        
                if (!empty($errors)) {
                    $_SESSION['errors']    = $errors;
                    $_SESSION['old-email'] = $email;
                    
                    // require_once __DIR__.'/../View/admin_login_view.php';
                    require_once __DIR__.'/../../Public/admin_login.php';
                    exit;

                } else {
                    session_regenerate_id(true);

                    $_SESSION['adminId']   = $admin['id'];
                    $_SESSION['adminName'] = $admin['firstName'] . ' ' . $admin['lastName']; // フルネームを保存

                    // require_once __DIR__.'/../View/product_add.php';
                    require_once __DIR__.'/../../Public/all_products.php';
                    exit;
                }
            }
        }
    }
?>