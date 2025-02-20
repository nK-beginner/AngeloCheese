




<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビューを投稿する</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- レビュー用 -->
    <link rel="stylesheet" href="../css/review.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="#" method="POST" class="form" novalidate id="review-form">
                <h2><span>R</span>eview<span>.</span></h2>

                <!-- 商品名 -->
                <label for="product-name">商品名</label>
                <input type="text" class="input product-name" id="product-name" name="product-name" placeholder="自動で入る" disabled>

                <!-- お客様表示名 -->
                <label for="display-name">お名前</label>
                <input type="text" class="input display-name" id="display-name" name="display-name" placeholder="例：山田 春子">

                <label for="gender">性別</label>
                <div class="gender-btns">
                    <!-- 女性 -->
                    <div class="gender-btn">
                        <input type="radio" id="female" value="female" name="gender">
                        <label for="female">女性</label>
                    </div>

                    <!-- 男性 -->
                    <div class="gender-btn">
                        <input type="radio" id="male"   value="male"   name="gender">
                        <label for="male">男性</label>                        
                    </div>

                    <!-- その他 -->
                    <div class="gender-btn">
                        <input type="radio" id="others" value="others" name="gender">
                        <label for="others">その他</label>                            
                    </div>
                </div>

                <!-- 満足度 -->
                <label for="stars">満足度</label>
                <div class="rating">
                    <div class="star" data-value="1">☆</div>
                    <div class="star" data-value="2">☆</div>
                    <div class="star" data-value="3">☆</div>
                    <div class="star" data-value="4">☆</div>
                    <div class="star" data-value="5">☆</div>
                </div>

                <!-- タイトル -->
                <label for="review-title">タイトル</label>
                <input type="text" class="input review-title" id="review-title" name="review-title" placeholder="20文字以内でご記入ください。" maxlength="20">

                <!-- 本文 -->
                <label for="main-sentence">本文</label>
                <textarea class="input main-sentence" id="main-sentence" name="main-sentence" placeholder="ご記入ください。"></textarea>

                <!-- 個人情報ポリシー -->
                <div class="accept-policy">
                    <div class="accept-btn">
                        <input type="radio" id="accept" value="accept" name="accept" class="policy-radio">
                        <label for="accept">個人情報ポリシーに同意してレビューを投稿します。</label>
                    </div>
                </div>

                 <!-- ボタン -->
                <div class="btns">
                    <button type="reset" class="discard">破棄</button>
                    <button type="submit" class="submit">投稿</button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script src="../JS/script.js"></script>
</body>
</html>