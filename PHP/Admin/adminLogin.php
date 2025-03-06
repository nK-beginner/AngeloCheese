




<?php
    session_start();
    require_once __DIR__ . '/Backend/connection.php';
    require_once __DIR__ . '/Backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    $email  = $_SESSION['old-email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old-email']);

    // CSRFトークンが未セットなら生成
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }

        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // 受け取り
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // バリデーション
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        if (empty($password)) {
            $errors[] = 'パスワードを入力してください。';
        }

        // レートリミットチェック（ブルートフォース対策：5回以上失敗で15分ログイン阻止）
        $ip = $_SERVER['REMOTE_ADDR'];
        $failed_login_key = 'failed_login_'.$ip;
        if (isset($_SESSION[$failed_login_key]) && $_SESSION[$failed_login_key]['count'] >= 5) {
            if (time() - $_SESSION[$failed_login_key]['last_attempt'] < 900) {
                $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';
            } else {
                unset($_SESSION[$failed_login_key]);
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo2 -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
                $stmt->bindValue(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$admin) {
                    $errors[] = '存在しないメールアドレスです。';
                } else if (!password_verify($password, $admin['password'])) {
                    $errors[] = 'パスワードが間違っています。';
                }

                if (!empty($errors)) {
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old-email'] = $email; // 入力されたメールアドレスをセッションに保存
                    header('Location: adminLogin.php');
                    exit;
                }

                // セッション固定攻撃対策
                session_regenerate_id(true);

                // セッションに id, firstName, lastName を保存
                $_SESSION['adminId'] = $admin['id'];
                $_SESSION['adminName'] = $admin['firstName'] . ' ' . $admin['lastName']; // フルネームを保存

                header('Location: itemAdd.php');
                exit;

            } catch (PDOException $e) {
                error_log('データベースエラー:' . $e->getMessage());
                $errors[] = 'データベース接続エラー';
            }            
        }
    }
?>



<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用ログイン画面</title>

    <!-- 管理者ログイン用CSS -->
    <link rel="stylesheet" href="CSS/adminLogin.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <form action="adminLogin.php" method="POST" class="login-form">
            <!-- CSRFトークン -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <img src="images/AngeloCheese_face.png" alt="アンジェロチーズロゴ">
            <h1>ログイン</h1>

            <!-- エラーメッセージ -->
            <?php if(!empty($errors)): ?>
                <div class="error-container">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>        
                    <?php endforeach; ?>
                </div>  
            <?php endif; ?>

            <div class="block">
                <h3>メールアドレス</h3>
                <input type="text" name="email" placeholder="メールアドレスを入力してください。" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            </div>
            
            <div class="block">
                <h3>パスワード</h3>
                <input type="password" name="password" placeholder="パスワードを入力してください。">
            </div>
            
            <input type="submit" class="submit-btn" value="ログイン">
        </form>
    </main>
</body>
</html>