<?php
    require_once __DIR__.'/../PHP/resetPassword.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードのリセット</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- パスワードリセット用CSS -->
    <link rel="stylesheet" href="../css/resetPassword.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="../PHP/resetPassword.php" method="POST" class="form">
                <h2><span>R</span>eset <span>P</span>assword<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- エラーメッセージ表示 -->
                <?php if(!empty($errors)): ?>
                    <?php foreach($errors as $error): ?>
                        <div class="help-link incorrect-pw">
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- パスワード -->
                <label for="password">新しいパスワードを入力してください。</label>
                <input type="password" class="input password" id="password" name="password" placeholder="パスワードを入力してください。" required>

                <!-- 確認用パスワード -->
                <label for="re-password">確認用：再度入力してください。</label>
                <input type="password" class="input password" id="re-password" name="re-password" placeholder="パスワードを再入力してください。" required>

                <!-- 再設定ボタン -->
                <input type="submit" class="input btn" id="reset-pw" name="reset-pw" value="パスワードを再設定する">
            </form>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>

</body>
</html>