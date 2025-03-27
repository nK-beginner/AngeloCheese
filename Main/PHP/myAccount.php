



<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(!isset($_SESSION['user_id']) || !isset($_COOKIE['remember_token'])) {

        header('Location: login.php');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員情報</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- アカウント情報用CSS -->
    <link rel="stylesheet" href="../css/myAccount.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <div class="form">
                <h2 class="page-title"><span>A</span>ccount<span>.</span></h2>
                <!-- 姓 -->
                <div class="profile-item">
                    <p>姓</p>
                    <div class="content">
                        <h3><?php echo htmlspecialchars(isset($_SESSION['firstName']) ? $_SESSION['firstName'] : 'ログインしていません。', ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                </div>

                <!-- 名 -->
                <div class="profile-item">
                    <p>名</p>
                    <div class="content">
                    <h3><?php echo htmlspecialchars(isset($_SESSION['lastName']) ? $_SESSION['lastName'] : 'ログインしていません。', ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                </div>

                <!-- メールアドレス -->
                <div class="profile-item">
                    <p>メールアドレス</p>
                    <div class="content">
                        <h3><?php echo htmlspecialchars(isset($_SESSION['email']) ? $_SESSION['email'] : 'ログインしていません。', ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                </div>

                <!-- パスワード -->
                <div class="profile-item">
                    <p>パスワード</p>
                    <div class="content">
                        <h3 style="letter-spacing: 2px;">************</h3>
                    </div>
                </div>

                <!-- 削除ボタン -->
                <div class="h-block">
                    <a href="#" class="edit">編集</a>
                    <a href="unsubscribe.php" class="delete-account">退会</a>
                </div>
                
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
                <button class="confirm logout-btn">ログアウト</button>
            </div>
        </div>
    </div>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script src="../JS/myAccount.js"></script>
</body>
</html>