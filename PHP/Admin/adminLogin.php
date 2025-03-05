




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

        try {
            $stmt = $pdo -> prepare("SELECT * FROM administrators WHERE email = :email");
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
            $stmt -> execute();
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
        
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
            <div class="block">
                <h3>メールアドレス</h3>
                <input type="text" name="email">
            </div>
            
            <div class="block">
                <h3>パスワード</h3>
                <input type="password" name="password">
            </div>
            
            <input type="submit" class="submit-btn" value="ログイン">
        </form>
    </main>
</body>
</html>