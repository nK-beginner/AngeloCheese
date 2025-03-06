




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
    <title>退会</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- 退会用CSS -->
    <link rel="stylesheet" href="../css/unsubscribe.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="confirmUnsubscribe.html" method="POST" class="form">
                <h2><span>U</span>nsubscribe<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token">

                <!-- 注意書き -->
                <div class="warning-container">
                    <p>アカウント退会を行います。</p>
                    <p>退会すると購入履歴などの閲覧ができなくなります。</p>
                </div>

                <!-- 退会理由 -->
                <div class="unsubscribe-reasons-container">
                    <div class="reasons">
                        <h3>退会理由</h3>

                        <!-- 理由1 -->
                        <div class="reason">
                            <input type="radio" id="reason1" value="reason1" name="reason">   
                            <label for="reason1">理由1</label>
                        </div>

                        <!-- 理由2 -->
                        <div class="reason">
                            <input type="radio" id="reason2" value="reason2" name="reason">   
                            <label for="reason2">理由2</label>
                        </div>

                        <!-- 理由3 -->
                        <div class="reason">
                            <input type="radio" id="reason3" value="reason3" name="reason">   
                            <label for="reason3">理由3</label>
                        </div>

                        <!-- その他 -->
                        <div class="reason">
                            <input type="radio" id="other" value="other" name="reason">   
                            <label for="other">その他</label>
                        </div>

                        <!-- 理由記入(その他選択の時のみ解放) -->
                        <textarea id="" name="reason" placeholder="理由をお聞かせください。"></textarea>
                    </div>
                    <input type="submit" value="確認画面へ" class="to-confirm">
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script src="../JS/script.js"></script>
</body>
</html>