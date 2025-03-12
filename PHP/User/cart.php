







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
    <meta name="theme-color" content="#242424">
    <title>ログイン</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- ログイン用CSS -->
    <link rel="stylesheet" href="../css/cart.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="login.php" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <h2 class="page-title"><span>C</span>art<span>.</span></h2>

                
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

</body>
</html>

