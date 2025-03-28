




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
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- パスワード忘れ時用CSS -->
    <link rel="stylesheet" href="../css/forgotPassword.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="#" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <h2 class="page-title"><span>R</span>eset <span>P</span>assword<span>.</span></h2>

                <div class="description">
                    <h4>
                        入力されたメールアドレス宛に<br>
                        リセット用のメールを送信いたします。
                    </h4>
                </div>

                <h4>メールアドレス</h4>
                <input class="user-input" type="email" id="email" name="email" value="<?php ?>" placeholder="angelo@example.com" required>

                <input class="submit-btn" type="submit" id="login" name="login" value="メールを送信">

                <a class="back-to-login" href="login.php">ログイン画面へ戻る</a>
            </form>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>