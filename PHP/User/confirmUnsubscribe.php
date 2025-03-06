




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
    <title>退会確認</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- 退会確認用CSS -->
    <link rel="stylesheet" href="../css/confirmUnsubscribe.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="unsubscribeDone.php" method="POST" class="form">
                <h2><span>U</span>nsubscribe<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token">

                <!-- パスワード入力 -->
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="input" placeholder="パスワードを入力してください。">

                <!-- パスワード再入力 -->
                <label for="re-password">確認用</label>
                <input type="password" id="re-password" name="re-password" class="input" placeholder="再度パスワードを入力してください。">

                <!-- 退会理由 -->
                <label>退会理由</label>
                <div class="reason-container">
                    <h3 class="reason">-理由1</h3>
                    <!-- その他の場合はここに記入されたものを表示 -->
                </div>
                <!-- 退会ボタン -->
                <input type="submit" value="退会する" class="confirm-unsubscribe">
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>
</body>
</html>