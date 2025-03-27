




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: onlineShop.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        // 退会理由受け取り
        $_SESSION['reason'] = $_POST['reason'] ?? '';

        if($_SESSION['reason'] == 99) {
            $_SESSION['reasonDetail'] = $_POST['reasonDetail'] ?? '';

        } else {
            $_SESSION['reasonDetail'] = NULL;
        }

        header('Location: confirmUnsubscribe.php');
        exit;
    }
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
            <form action="unsubscribe.php" method="POST">
                <h2 class="page-title"><span>U</span>nsubscribe<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- 注意書き -->
                <div class="warning-container">
                    <p>アカウント退会を行います。</p>
                    <p>※退会すると購入履歴などの閲覧ができなくなります。</p>
                </div>

                <!-- 退会理由 -->
                <div class="unsubscribe-reasons-container">
                    <div class="reasons">
                        <h2>退会理由</h2>

                        <!-- 理由1 -->
                        <div class="reason">
                            <label for="reason1"><input class="reason-btn" type="radio" id="reason1" value="1" name="reason" required> 理由1</label>
                        </div>

                        <!-- 理由2 -->
                        <div class="reason">
                            <label for="reason2"><input class="reason-btn" type="radio" id="reason2" value="2" name="reason" required> 理由2</label>
                        </div>

                        <!-- 理由3 -->
                        <div class="reason">
                            <label for="reason3"><input class="reason-btn" type="radio" id="reason3" value="3" name="reason" required> 理由3</label>
                        </div>

                        <!-- その他 -->
                        <div class="reason">
                            <label for="other"><input type="radio" id="other" value="99" name="reason"> その他</label> 
                        </div>

                        <!-- 理由記入(その他選択の時のみ解放) -->
                        <textarea id="reasonDetail" name="reasonDetail" placeholder="その他を選択された方は理由をお聞かせください。" maxlength="170"></textarea>
                    </div>
                    <input type="submit" value="確認画面へ" class="to-confirm">
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script src="../JS/unsubscribe.js"></script>
</body>
</html>