




<?php
    session_start();
    require_once __DIR__.'/backend/connection.php';

    // クッキーのremember_tokenがあれば自動ログイン
    if(!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        $stmt = $pdo -> prepare('select id, username from test_users where remember_token = :token');
        $stmt -> bindValue(':token', $token, PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch();

        // ユーザーが存在すればSESSION_idにはDB上のid,SESSION_usernameにはDB上のusernameをセット 
        if($user) {

            // セッション固定攻撃対策
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
        }
    }

    // ログインしていない場合はゲストとして閲覧
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'ゲスト';
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テストページ</title>
</head>
<body>
    <h1>
        <?php echo 'こんにちは、'.htmlspecialchars($username, ENT_QUOTES, 'UTF-8').'さん'; ?>
    </h1>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="user/login.php">ログイン</a>
    <?php else: ?>
        <a href="user/logout.php">ログアウト</a>
    <?php endif; ?>
</body>
</html>