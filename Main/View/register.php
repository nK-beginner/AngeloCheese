<?php
    require_once __DIR__.'/../PHP/register.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>

    <!-- 新規登録用 -->
    <link rel="stylesheet" href="../css/register.css?v=<?php echo time(); ?>">

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="grid-container">
            <div class="welcome-container">
                <img src="/../AngeloCheese/images/AngeloCheese_logo1.png" alt="ロゴ">
                <div class="sub-container">
                    <h1>ようこそ。</h1>
                </div>
            </div>

            <div class="main-container">
                <form action="../PHP/Register.php" method="POST">
                    <!-- CSRFトークン -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <h2 class="page-title"><span>R</span>egister<span>.</span></h2>

                    <?php if(!empty($errors)): ?>
                        <div class="error-msg">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h4>姓</h4>
                    <input class="user-input" type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="山田" maxlength="15" required>

                    <h4>姓</h4>
                    <input class="user-input" type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="花子" maxlength="15" required>

                    <h4>メールアドレス</h4>
                    <input class="user-input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="angelo@example.com" required>

                    <h4>パスワード</h4>
                    <input class="user-input" type="password" id="password" name="password" placeholder="パスワード(8文字以上)" minlength="8" required>

                    <input class="submit-btn" type="submit" id="register" name="register" value="登録">
                </form>
                <!-- アカウント登録画面へ -->
                <p class="info">アカウントをお持ちですか？</p>
                <a class="log-reg-btn" href="Login.php">ログイン</a> 
            </div>
        </div>

    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>