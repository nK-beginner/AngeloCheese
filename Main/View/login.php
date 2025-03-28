<?php
    require_once __DIR__.'/../PHP/login.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>ログイン</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- ログイン用CSS -->
    <link rel="stylesheet" href="../css/Login.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="grid-container">
            <div class="welcome-container">
            <img src="/../AngeloCheese/images/AngeloCheese_logo1.png" alt="ロゴ">
                <div class="sub-container">
                    <h1>おかえりなさいませ。</h1>
                </div>
            </div>

            <div class="main-container">
                <form action="../PHP/login.php" method="POST">
                    <!-- CSRFトークン -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <h2 class="page-title"><span>L</span>ogin<span>.</span></h2>

                    <?php if(!empty($errors)): ?>
                        <div class="error-msg">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h4>メールアドレス</h4>
                    <input class="user-input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="angelo@example.com" required>

                    <h4>パスワード</h4>
                    <input class="user-input" type="password" id="password" name="password" placeholder="パスワード(8文字以上)" minlength="8" required>

                    <input class="submit-btn" type="submit" id="login" name="login" value="ログイン">

                    <a class="forgot-pw" href="forgotPassword.php">パスワードをお忘れですか？</a> 
                </form>
                <p class="info">アカウントをお持ちでない方はこちら。</p>
                <a class="log-reg-btn" href="Register.php">新規会員登録</a>
            </div>            
        </div>

    </main>

    <?php include __DIR__.'../common/footer.php'; ?>

</body>
</html>