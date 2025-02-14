




<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>
<body>
    <h1>ログアウトしました。</h1>
    <a href="login.php">ログインページへ戻る</a>
</body>
</html>