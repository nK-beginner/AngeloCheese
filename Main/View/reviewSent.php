<?php
    require_once __DIR__.'/../PHP/reviewSent.php.php';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー投稿完了</title>

    <!-- headタグ -->
    <?php include __DIR__.'../common/headTags.php'; ?>

    <!-- レビュー投稿完了用CSS -->
    <link rel="stylesheet" href="../css/reviewSent.css?v=<?php echo time(); ?>">
    
</head>
<body>
<?php include __DIR__.'../common/header.php'; ?>

    <main>
        <div class="thankyou-container">
            <h1>Thank You<span>.</span></h1>
                <h3><i class="fa-solid fa-circle-check"></i>レビューの投稿が完了しました。</h3>
            
            <div class="btns">
                <a href="#"><button class="add-another-review">他のレビューを<br>投稿する</button></a>
                <a href="#"><button class="to-top-page">トップ画面へ</button></a>
            </div>
        </div>

        <div class="reccomended-container">
            <h2><span>R</span>ccomended<span>.</span></h2>
        </div>
    </main>

    <?php include __DIR__.'../common/footer.php'; ?>
</body>
</html>