




<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード更新完了</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- パスワード更新完了用CSS -->
    <link rel="stylesheet" href="../css/resetPasswordDone.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <div class="form">
                    <h2><span>R</span>eset <span>P</span>assword<span>.</span></h2>
                    <h3 class="reset-done"><span>✔</span>パスワードの変更が完了しました。</h3>

                    <a href="login.php" class="to-login-a"><button class="btn to-login-btn">ログイン画面へ</button></a>
            </div>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>
</body>
</html>