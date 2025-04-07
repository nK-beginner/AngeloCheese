<?php require_once __DIR__.'/../PHP/adminRegister.php' ?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用アカウント登録画面</title>

    <!-- ページアイコン -->
    <link rel="icon" href="../Admin/images/AngeloCheese_face.png">

    <!-- 管理者登録用CSS -->
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/adminRegister.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <div class="main-container">
            <form action="adminRegister.php" method="POST" class="register-form">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <img src="/../AngeloCheese/images/Gold/AngeloCheese_logo3.png" alt="アンジェロチーズロゴ">
                <h1>アカウント登録</h1>
                
                <!-- エラーメッセージ -->
                <?php if(!empty($errors)): ?>
                    <div class="error-container">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>        
                        <?php endforeach; ?>
                    </div>  
                <?php endif; ?>

                <!-- 氏名 -->
                <div class="name-block">
                    <div class="block">
                        <h3>氏</h3>
                        <input type="text" name="first-name" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="例：山田" maxlength="10">
                    </div>

                    <div class="block">
                        <h3>名</h3>
                        <input type="text" name="last-name" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="例：太郎" maxlength="10">
                    </div>
                </div>
                
                <!-- メアド -->
                <div class="block">
                    <h3>メールアドレス</h3>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="メールアドレスを入力してください。">
                </div>
                
                <!-- パスワード -->
                <div class="block">
                    <h3>パスワード</h3>
                    <input type="password" name="password" placeholder="英数字記号を含む8文字以上で入力してください。" minlength="8">
                </div>
                
                <!-- 送信ボタン -->
                <input type="submit" class="submit-btn" value="登録">
            </form>            
        </div>
    </main>
</body>
</html>