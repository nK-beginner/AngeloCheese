<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用ログイン</title>
    <link rel="icon" href="/../Test/Public/Images/Gold/AngeloCheese_logo3.png">
    <link rel="stylesheet" href="/../Test/Public/CSS/admin_register.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <div class="main-container">
            <form action="/Test/Public/admin_register.php" method="POST" class="register-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <img src="/../AngeloCheese/images/Gold/AngeloCheese_logo3.png" alt="アンジェロチーズロゴ">
                <h1>アカウント登録</h1>
                
                <?php if(!empty($errors)): ?>
                    <div class="error-container">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>        
                        <?php endforeach; ?>
                    </div>  
                <?php endif; ?>

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
                
                <div class="block">
                    <h3>メールアドレス</h3>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="メールアドレスを入力してください。">
                </div>
                
                <div class="block">
                    <h3>パスワード</h3>
                    <input type="password" name="password" placeholder="英数字記号を含む8文字以上で入力してください。" minlength="8">
                </div>
                
                <input type="submit" class="submit-btn" value="登録">
            </form>            
        </div>
    </main>
</body>
</html>