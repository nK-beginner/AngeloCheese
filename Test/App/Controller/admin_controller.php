<?php
    require_once __DIR__.'/../Model/admin_model.php';
    require_once __DIR__.'/../../Utils/functions.php';

    class AdminLoginController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Admin($pdo);
        }

        public function handleRequest() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $errors = $_SESSION['errors'] ?? [];
            $email  = $_SESSION['old-email'] ?? '';

            unset($_SESSION['errors'], $_SESSION['old-email']);

            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    die('CSRFトークン不一致エラー');
                }

                unset($_SESSION['csrf_token']);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                $email    = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = '有効なメールアドレスを入力してください。';
                }

                if (empty($password)) {
                    $errors[] = 'パスワードを入力してください。';
                }

                if (empty($errors)) {
                    try {
                        $admin = $this->model->getUserByEmail($email);

                        if (!$admin) {
                            $errors[] = '存在しないメールアドレスです。';
                        } else if (!password_verify($password, $admin['password'])) {
                            $errors[] = 'パスワードが間違っています。';
                        }

                        if (!empty($errors)) {
                            $_SESSION['errors'] = $errors;
                            $_SESSION['old-email'] = $email;
                            header('Location: admin_login.php');
                            exit;
                        }

                        session_regenerate_id(true);
                        $_SESSION['adminId'] = $admin['id'];
                        $_SESSION['adminName'] = $admin['firstName'] . ' ' . $admin['lastName'];
                        header('Location: all_products.php');
                        exit;

                    } catch (PDOException $e) {
                        error_log('データベースエラー:' . $e->getMessage());
                        $errors[] = 'データベース接続エラー';
                    }
                }

                $_SESSION['errors'] = $errors;
                $_SESSION['old-email'] = $email;
                header('Location: admin_login.php');
                exit;
            }

            require_once __DIR__ . '/../View/admin_login_view.php';
        }
    }
?>