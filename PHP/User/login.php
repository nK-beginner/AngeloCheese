




<?php
    session_start();
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

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
        if(empty($errors)) {
            $stmt = $pdo -> prepare('select * from test_users where email = :email limit 1');
            $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
            $stmt -> execute();
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);

            // ユーザーが存在し、ハッシュ化パスワードと等しければ通す
            if($user && password_verify($password, $user['password'])) {
                // セッション固定攻撃対策
                session_regenerate_id(true);

                // セッション各情報へデータ格納
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email']    = $user['email'];

                // クッキー設定：チェックがついてれば設定する
                if(isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $expire = time() + (7 * 24 * 60 * 60);
                    setcookie('remember_token', $token, $expire, '/', '', false, false); // ※公開時はfalse, trueにすること：HttpOnlyに

                    // クッキー用のトークンを生成
                    $stmt = $pdo -> prepare("UPDATE test_users SET remember_token = :token WHERE id = :id");
                    $stmt -> bindValue(':token', $token, PDO::PARAM_STR);
                    $stmt -> bindValue(':id', $user['id'], PDO::PARAM_INT);
                    $stmt -> execute();
                }

                header('Location: onlineShop.php');
                exit;

            } else {
                // ログイン失敗回数を記録
                $_SESSION[$failed_login_key]['count'] = ($_SESSION[$failed_login_key]['count'] ?? 0) + 1; 

                // 最後にログイン失敗した時刻を保存
                $_SESSION[$failed_login_key]['last_attempt'] = time(); 
                
                $errors[] = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_email']  = $email;

            header('Location: login.php');
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
    <link rel="stylesheet" href="../css/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="login.php" method="POST" class="form">
                <h2><span>L</span>ogin<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- エラーメッセージ -->
                <?php if(!empty($errors)): ?>
                    <div class="error-msg">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- メールアドレス -->
                <h4>メールアドレス</h4>
                <input type="email" class="input email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="メールアドレス" required>

                <!-- パスワード -->
                <h4>パスワード</h4>
                <input type="password" class="input password" id="password" name="password" placeholder="パスワード(8文字以上)">

                <!-- COOKIE許可・拒否 -->
                <label class="cookie"><input type="checkbox" name="remember"> ログイン情報を記憶</label><br>

                <!-- ログインボタン -->
                <input type="submit" class="input btn" id="login" name="login" value="ログイン">

                <!-- パスワードリセット画面へ -->
                <a href="forgotPassword.php" class="help-link forgot-password">パスワードをお忘れですか？</a> 
            </form>
            <!-- アカウント登録画面へ -->
            <p class="help-link not-registered-yet">アカウントをお持ちでない方はこちら。</p>
            <a href="register.php" class="register-login-btn">新規会員登録</a>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

</body>
</html>