<?php
    require_once __DIR__.'/../PHP/confirmUnsubscribe.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>退会確認</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- 退会確認用CSS -->
    <link rel="stylesheet" href="../css/confirmUnsubscribe.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="../PHP/confirmUnsubscribe.php" method="POST" class="form">
                <h2 class="page-title"><span>U</span>nsubscribe<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <?php if(!empty($errors)): ?>
                    <div class="error-msg">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h4>お名前</h4>
                <input class="user-input" type="text" value="<?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName'] . ' ' . '様', ENT_QUOTES, 'UTF-8'); ?>" readonly>

                <!-- パスワード入力 -->
                <h4>パスワード</h4>
                <input class="user-input" type="password" id="password" name="password" placeholder="パスワードを入力してください。" required>

                <!-- パスワード再入力 -->
                <h4>確認用</h4>
                <input class="user-input" type="password" id="re-password" name="rePassword" placeholder="再度パスワードを入力してください。" required>

                <!-- 退会理由 -->
                <h4>退会理由</h4>
                <div class="reason-container">
                    <h3 class="reason">・<?php echo htmlspecialchars($_SESSION['reason'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <!-- その他の場合はここに記入されたものを表示 -->
                    <?php if(!empty($_SESSION['reasonDetail'])): ?>
                        <p><?php echo htmlspecialchars($_SESSION['reasonDetail'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <!-- 退会ボタン -->
                <input type="submit" value="退会する" class="confirm-unsubscribe">
            </form>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>

</body>
</html>