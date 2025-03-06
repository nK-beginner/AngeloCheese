




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード忘れ</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- パスワード忘れ時用CSS -->
    <link rel="stylesheet" href="../css/forgotPassword.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="#" class="form">
                <h2><span>R</span>eset <span>P</span>assword<span>.</span></h2>
                <div class="description">
                    <h4>
                        入力されたメールアドレス宛に<br>
                        リセット用のメールを送信いたします。
                    </h4>
                </div>

                <!-- メールアドレス -->
                <label for="email">メールアドレス</label>
                <input type="email" class="input email" id="email" name="email" placeholder="メールアドレスを入力してください。" required>

                <!-- 送信ボタン -->
                <input type="submit" class="input btn" id="resetPw" name="resetPw" value="メールを送る">

                <!-- ログイン画面へ -->
                <a href="login.php" class="back-to-login">ログイン画面へ戻る</a>
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>
</body>
</html>