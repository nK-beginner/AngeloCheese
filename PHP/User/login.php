




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    if(isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])) {
        header('Location: onlineShop.php');
        exit;
    }

    $errors = $_SESSION['errors'] ?? [];
    $email = $_SESSION['old_email'] ?? '';

    unset($_SESSION['errors'], $_SESSION['old_email']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }

        // CSRFトークン再生成：既存のトークンを無効化し再生成 ⇒ 使い回しを防ぐ
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワード入力チェック
        if(empty($password)) {
            $errors[] = 'パスワードを入力してください。';
        }

        // レートリミットチェック（ブルートフォース対策：5回以上失敗で15分ログイン阻止）
        $ip = $_SERVER['REMOTE_ADDR'];
        $failed_login_key = 'failed_login_'.$ip;
        if(isset($_SESSION[$failed_login_key]) && $_SESSION[$failed_login_key]['count'] >= 5) {
            if(time() - $_SESSION[$failed_login_key]['last_attempt'] < 900) {
                $errors[] = 'ログイン試行回数が多すぎます。しばらくしてから再試行してください。';

            } else {
                unset($_SESSION[$failed_login_key]);
            }
        }
        
        // エラーがなければSQL確認
        try {
            if(empty($errors)) {
                $stmt = $pdo -> prepare("SELECT id, firstName, lastName, email, password FROM test_users WHERE email = :email LIMIT 1");
                $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
                $stmt -> execute();
                $user = $stmt -> fetch(PDO::FETCH_ASSOC);

                if($user && password_verify($password, $user['password'])) {
                    // セッション固定攻撃対策
                    session_regenerate_id(true);

                    // セッション各情報へデータ格納
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['firstName'] = $user['firstName'];
                    $_SESSION['lastName']  = $user['lastName'];
                    $_SESSION['email']     = $user['email'];

                    // クッキー
                    require_once __DIR__.'/../Backend/cookie.php';

                    if(isset($_SESSION['fromCart'])) {
                        header('Location: cart.php');
                        exit;

                    } else {
                        header('Location: onlineShop.php');
                        exit;
                    }


                } elseif($user['deleted_at'] !== NULL) {
                    $errors[] = '存在しないユーザーか、削除されたユーザーです。';

                } else {
                    // ログイン失敗回数を記録
                    $_SESSION[$failed_login_key]['count'] = ($_SESSION[$failed_login_key]['count'] ?? 0) + 1; 

                    // 最後にログイン失敗した時刻を保存
                    $_SESSION[$failed_login_key]['last_attempt'] = time(); 
                    
                    $errors[] = 'メールアドレスまたはパスワードが間違っています。';
                }
            }

        } catch(PDOException $e) {
            error_log('データベース接続エラー：' . $e -> getMessage());
            $error[] = 'データベース接続エラー';
        }


        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_email']  = $email;

            header('Location: Login.php');
            exit;
        }
    }
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
    <link rel="stylesheet" href="../css/Login.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="grid-container">
            <div class="welcome-container">
            <img src="/../AngeloCheese/images/AngeloCheese_logo1.png" alt="ロゴ">
                <div class="sub-container">
                    <h1>おかえりなさいませ。</h1>
                </div>
            </div>

            <div class="main-container">
                <form action="login.php" method="POST">
                    <!-- CSRFトークン -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <h2 class="page-title"><span>L</span>ogin<span>.</span></h2>

                    <?php if(!empty($errors)): ?>
                        <div class="error-msg">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h4>メールアドレス</h4>
                    <input class="user-input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="angelo@example.com" required>

                    <h4>パスワード</h4>
                    <input class="user-input" type="password" id="password" name="password" placeholder="パスワード(8文字以上)" minlength="8" required>

                    <input class="submit-btn" type="submit" id="login" name="login" value="ログイン">

                    <a class="forgot-pw" href="forgotPassword.php">パスワードをお忘れですか？</a> 
                </form>
                <p class="info">アカウントをお持ちでない方はこちら。</p>
                <a class="log-reg-btn" href="Register.php">新規会員登録</a>
            </div>            
        </div>

    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

</body>
</html>