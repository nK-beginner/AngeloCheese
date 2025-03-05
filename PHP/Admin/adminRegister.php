




<?php
    session_start();
    require_once __DIR__ . '/Backend/connection.php';
    require_once __DIR__ . '/Backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // 受け取り
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rePassword = trim($_POST['re-password'] ?? '');

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        try {
            $stmt = $pdo -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
            $stmt -> execute();
            $admin = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($admin) {
                $errors[] = 'このメールアドレスは既に登録されています。';
            }

            if(!empty($errors)) {
                header('Location: adminRegister.php');
                exit;
            }

            // パスワードハッシュ化
            $hashedPassword = password_hash($password);

        } catch(PDOException $e) {
            $errors[] = 'データベース接続エラー';
        }

    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用アカウント登録画面</title>

    <!-- 管理者登録用CSS -->
    <link rel="stylesheet" href="CSS/adminRegister.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <form action="adminRegister.php" method="POST" class="register-form">
            <!-- CSRFトークン -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <img src="images/AngeloCheese_face.png" alt="アンジェロチーズロゴ">
            <h1>アカウント登録</h1>
            <div class="block">
                <h3>メールアドレス</h3>
                <input type="text" name="email" placeholder="メールアドレスを入力してください。">
            </div>
            
            <div class="block">
                <h3>パスワード</h3>
                <input type="password" name="password" placeholder="英数字記号を含む8文字以上で入力してください。">
            </div>

            <div class="block">
                <h3>パスワード（確認用）</h3>
                <input type="password" name="re-password" placeholder="同じパスワードを入力してください。">
            </div>
            
            <input type="submit" class="submit-btn" value="登録">
        </form>
    </main>
</body>
</html>