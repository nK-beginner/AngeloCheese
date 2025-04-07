<?php require_once __DIR__.'/../PHP/adminLogin.php' ?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用ログイン画面</title>

    <!-- ページアイコン -->
    <link rel="icon" href="/../AngeloCheese/images/Gold/AngeloCheese_logo3.png">

    <!-- 管理者ログイン用CSS -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/adminLogin.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <form action="adminLogin.php" method="POST" class="login-form">
            <!-- CSRFトークン -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <img src="/../AngeloCheese/images/Gold/AngeloCheese_logo3.png" alt="アンジェロチーズロゴ">
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