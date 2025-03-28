




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
    <title>退会完了</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- 退会完了用CSS -->
    <link rel="stylesheet" href="../css/unsubscribeDone.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="main-container">
            <div class="unsubscribe-done-container">
                <h3><i class="fa-solid fa-circle-check"></i>退会が完了しました。</h3>
                <P>ゲストとしても商品を閲覧できますので、ごゆっくりとお過ごしくださいませ。</P>
            </div>
            <!-- 商品一覧画面へ -->
            <a href="#" class="to-top">商品一覧へ</a>
        
            <div class="recommended-container">
                <h2 class="page-title"><span>R</span>ccomended<span>.</span></h2>
            </div>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>