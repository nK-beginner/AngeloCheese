<?php
    require_once __DIR__.'/../Model/admin_model.php';
    require_once __DIR__.'/../../Utils/functions.php';

    class AdminController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Admin($pdo);
        }

        public function handleLoginRequest() {
            $errors = $_SESSION['errors'] ?? [];
            $email  = $_SESSION['old-email'] ?? '';

            unset($_SESSION['errors'], $_SESSION['old-email']);

            if(!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            if($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }

                require_once __DIR__ . '/../View/admin_login_view.php';
                return;
            }

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(!fncCheckCSRF()) {
                    $errors[] = '不正アクセスです。';
                    $_SESSION['errors'] = $errors;
                    header('Location: admin_login.php');
                    exit;
                }

                $email    = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = '有効なメールアドレスを入力してください。';
                }

                if (empty($password)) {
                    $errors[] = 'パスワードを入力してください。';
                } elseif(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
                    $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
                }

                try {
                    $admin = $this->model->getByEmail($email);

                    if (!$admin) {
                        $errors[] = '存在しないメールアドレスです。';
                    } else if (!password_verify($password, $admin['password'])) {
                        $errors[] = 'メールアドレスか、パスワードが間違っています。';
                    }
                } catch(PDOException $e) {
                    error_log('データベースエラー:' . $e->getMessage());
                    $errors[] = 'データベース接続エラー。';
                }

                if (!empty($errors)) {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old-email'] = $email;
                    header('Location: admin_login.php');
                    exit;
                }

                session_regenerate_id(true);

                $_SESSION['adminId']   = $admin['id'];
                $_SESSION['adminName'] = $admin['firstName'] . ' ' . $admin['lastName'];
                header('Location: all_products.php');
                exit;
            }
            require_once __DIR__ . '/../View/admin_login_view.php';
        }

        public function handleRegisterRequest() {
            $errors      = $_SESSION['errors'] ?? [];
            $firstName   = $_SESSION['old-firstName'] ?? '';
            $lastName    = $_SESSION['old-lastName'] ?? '';
            $email       = $_SESSION['old-email'] ?? '';

            unset($_SESSION['errors'], $_SESSION['old-firstName'], $_SESSION['old-lastName'], $_SESSION['old-email']);

            if($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }

                require_once __DIR__ . '/../View/admin_register_view.php';
                return;
            }
 
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(!fncCheckCSRF()) {
                    $errors[] = '不正アクセスです。';
                    $_SESSION['errors'] = $errors;
                    header('Location: admin_login.php');
                    exit;
                }

                $firstName = trim($_POST['first-name'] ?? '');
                $lastName  = trim($_POST['last-name'] ?? '');
                $email     = trim($_POST['email'] ?? '');
                $password  = trim($_POST['password'] ?? '');

                if(empty($firstName)) { $errors[] = '苗字を入力してください。'; }
                if(empty($lastName))  { $errors[] = '名前を入力してください。'; }
                if(empty($email)) { $errors[] = 'メールアドレスを入力してください。'; }
                    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = '有効なメールアドレスを入力してください。'; }
                if(empty($password)) { $errors[] = 'パスワードを入力してください。'; } 
                    elseif(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) { $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。'; }

                try {
                    $admin = $this->model->getByEmail($email);
                    if($admin) { $errors[] = '既に登録されているメールアドレスです。'; } 
                    
                } catch(PDOException $e) {
                    error_log('データベースエラー:' . $e->getMessage());
                    $errors[] = 'データベース接続エラー。';
                }

                if(!empty($errors)) {
                    $_SESSION['errors']        = $errors;
                    $_SESSION['old-firstName'] = $firstName;
                    $_SESSION['old-lastName']  = $lastName;
                    $_SESSION['old-email']     = $email;

                    header('Location: admin_register.php');
                    exit;
                }

                try {
                    $this->model->beginTransaction();

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $this->model->saveAdmin($firstName, $lastName, $email, $hashedPassword);

                    session_regenerate_id(true);

                    $this->model->commit();

                    header('Location: admin_login.php');
                    exit;

                } catch(PDOException $e) {
                    $this->model->rollBack();
                    error_log("ユーザー登録エラー: " . $e -> getMessage());

                    $_SESSION['errors']        = '登録処理中にエラーが発生しました。もう一度お試しください。';
                    $_SESSION['old-firstName'] = $firstName;
                    $_SESSION['old-lastName']  = $lastName;
                    $_SESSION['old-email']     = $email;

                    header('Location: admin_register.php');
                    exit;
                }
            }
        }
    }
?>