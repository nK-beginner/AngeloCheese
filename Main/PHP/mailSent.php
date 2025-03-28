




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
    <title>メール送信完了</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- メール送信用CSS -->
    <link rel="stylesheet" href="../css/mailSent.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
        <form action="#" class="form">
                <h2><span>R</span>eset <span>P</span>assword<span>.</span></h2>
                <div class="check-mail">
                    <h3 class="sent-mail"><span>✔</span>送信が完了しました。</h3>
                </div>

                <!-- 送信ボタン -->
                <h3 class="not-received">確認メールが届かない場合</h3>
                <input type="submit" class="input btn not-received-btn" id="not-received" name="not-received" value="再送する">
            </form>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>