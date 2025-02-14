




<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員情報</title>
    <!-- アカウント情報用CSS -->
    <link rel="stylesheet" href="../css/myaccount.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <div class="form">
                <h2><span>A</span>ccount<span>.</span></h2>
                <!-- ユーザー名 -->
                <div class="profile-item">
                    <p>ユーザー名</p>
                    <div class="content">
                        <h3>山田 太郎</h3>
                        <a href="#">変更</a>
                    </div>
                </div>

                <!-- メールアドレス -->
                <div class="profile-item">
                    <p>メールアドレス</p>
                    <div class="content">
                        <h3>t.yamada@example.com</h3>
                        <a href="#">変更</a>
                    </div>
                </div>

                <!-- パスワード -->
                <div class="profile-item">
                    <p>パスワード</p>
                    <div class="content">
                        <h3 style="letter-spacing: 2px;">************</h3>
                        <a href="#">変更</a>
                    </div>
                </div>

                <!-- 削除ボタン -->
                <a href="#" class="delete-account">退会する</a>
            </div>
            <!-- ログアウト -->
            <div class="logout-container">
                <a href="#" class="logout-button">ログアウト</a>
            </div> 
        </div>
    </main>
    <!-- ログアウトダイアログ -->
    <div class="logout-wrapper">
        <div class="logout-dialog">
            <div class="close-btn">✖</div>
            <p>ログアウトします。よろしいですか？</p>
            <div class="logout-btns">
                <button class="cancel logout-btn">キャンセル</button>
                <button class="confirm logout-btn">OK</button>                    
            </div>
        </div>
    </div>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script src="../JS/script.js"></script>
</body>
</html>