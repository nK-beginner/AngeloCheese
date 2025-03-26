




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    
    if(!isset($_SESSION['user_id'])) {
        header('Location: onlineShop.php');
        exit;
    }

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        $password   = $_POST['password'] ?? '';
        $rePassword = $_POST['rePassword'] ?? '';

        if($password !== $rePassword) {
            $errors[] = '確認用とパスワードが一致しません。';
        }

        $stmt = $pdo -> prepare("SELECT * FROM test_users WHERE email = :email LIMIT 1");
        $stmt -> bindValue(":email", $_SESSION['email'], PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors[] = 'ユーザーが見つかりません。';
        } 
        
        if (!password_verify($password, $user['password'])) {
            $errors[] = 'パスワードが異なります。'; 
        }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: confirmUnsubscribe.php');
            exit;
        }

        try {
            $pdo -> beginTransaction();
            
            $stmt = $pdo -> prepare("INSERT INTO deletedUsers (userId,  firstName,  lastName,  email, reason, reasonDetail)
                                                        VALUES(:userId, :firstName, :lastName, :email, :reason, :reasonDetail)");
            $stmt -> bindValue(":userId",    $user['id'],        PDO::PARAM_INT);
            $stmt -> bindValue(":firstName", $user['firstName'], PDO::PARAM_STR);
            $stmt -> bindValue(":lastName",  $user['lastName'],  PDO::PARAM_STR);
            $stmt -> bindValue(":email",     $user['email'],     PDO::PARAM_STR);
            $stmt -> bindValue(":reason",    intval($_SESSION['reason']), PDO::PARAM_INT);
            $stmt -> bindValue(":reasonDetail",    $_SESSION['reasonDetail'], PDO::PARAM_STR);
            $stmt -> execute();

            $pdo -> commit();

            session_destroy();

            if(isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }

            setcookie('remember_token', '', time() - 3600, '/', '', false, true);

            header('Location: unsubscribeDone.php');
            exit;

        } catch(PDOException $e) {
            $pdo -> rollBack();
            error_log('データベース接続エラー' . $e -> getMessage());
            
            $_SESSION['errors'][] = 'データベースエラーが発生しました。時間をおいて再試行してください。';

            header('Location: confirmUnsubscribe.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>退会確認</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- 退会確認用CSS -->
    <link rel="stylesheet" href="../css/confirmUnsubscribe.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="confirmUnsubscribe.php" method="POST" class="form">
                <h2 class="page-title"><span>U</span>nsubscribe<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <?php if(!empty($errors)): ?>
                    <div class="error-msg">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h4>お名前</h4>
                <input class="user-input" type="text" value="<?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName'] . ' ' . '様', ENT_QUOTES, 'UTF-8'); ?>" readonly>

                <!-- パスワード入力 -->
                <h4>パスワード</h4>
                <input class="user-input" type="password" id="password" name="password" placeholder="パスワードを入力してください。" required>

                <!-- パスワード再入力 -->
                <h4>確認用</h4>
                <input class="user-input" type="password" id="re-password" name="rePassword" placeholder="再度パスワードを入力してください。" required>

                <!-- 退会理由 -->
                <h4>退会理由</h4>
                <div class="reason-container">
                    <h3 class="reason">・<?php echo htmlspecialchars($_SESSION['reason'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <!-- その他の場合はここに記入されたものを表示 -->
                    <?php if(!empty($_SESSION['reasonDetail'])): ?>
                        <p><?php echo htmlspecialchars($_SESSION['reasonDetail'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <!-- 退会ボタン -->
                <input type="submit" value="退会する" class="confirm-unsubscribe">
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

    <script>
        
    </script>
</body>
</html>