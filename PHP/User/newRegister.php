




<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php'; // データベース接続
    require_once __DIR__.'/../backend/csrf_token.php'; // CSRFトークン生成
    
    $errors   = $_SESSION['errors'] ?? [];       // $_SESSION['errors']が存在しない or Nullの場合は空の配列[]が入る
    $fistName = $_SESSION['old_fistName'] ?? ''; // エラー時にユーザー名を保持
    $email    = $_SESSION['old_email'] ?? '';    // エラー時にメアドを保持
    
    unset($_SESSION['errors'], $_SESSION['old_fistName'], $_SESSION['old_email']); // 一度表示したら削除

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = []; // 入力エラー用配列

        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $errors[] = 'CSRFトークン不一致エラー';
        }

        // CSRFトークン再生成：既存のトークンを無効化し再生成 ⇒ 使い回しを防ぐ
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // フォームデータ取得とサニタイズ
        $fistName = trim($_POST['fistName'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        // メアド重複チェック
        $stmt = $pdo -> prepare('select id from test_users where email = :email limit 1');
        $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch();

        if($user) {
            $errors[] = 'このメールアドレスは既に登録されています。';
        }

        // レートリミットチェック（スパム対策）
        $ip = $_SERVER['REMOTE_ADDR'];
        $register_key = 'register_attempt_'.$ip;
        if(isset($_SESSION[$register_key]) && $_SESSION[$register_key]['count'] >= 3) {
            if(time() - $_SESSION[$register_key]['last_attempt'] < 3600) {
                $errors[] = 'アカウント登録の試行回数が多すぎます。しばらくしてから再試行してください。';
            } else {
                unset($_SESSION[$register_key]);
            }
        }

        // エラーあったら特定の情報を残しつつ、フォームへ戻る
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_fistName'] = $fistName;
            $_SESSION['old_email'] = $email;
            header('Location: register.php');
            exit;
        }
        
        // パスワードをハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ユーザー登録処理
        $stmt = $pdo -> prepare('insert into test_users (fistName, email, password, created_at, updated_at)
                                                 values(:fistName, :email, :password, now(), now())');
        $stmt -> bindValue(':fistName', $fistName, PDO::PARAM_STR);
        $stmt -> bindValue(':email',    $email,    PDO::PARAM_STR);
        $stmt -> bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $stmt -> execute();

        // セッション固定攻撃対策
        session_regenerate_id(true);

        // 登録後に自動ログイン
        $_SESSION['user_id'] = $pdo -> lastInsertId();
        $_SESSION['fistName'] = $fistName;

        header('Location: login.php');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>

    <link rel="stylesheet" href="../css/newRegister.css?v=<?php echo time(); ?>">

</head>
<body>
    <main>
        <div class="main-container">
            <form action="register.php" method="POST">
                <h2><span>R</span>egister<span>.</span></h2>

                <!-- エラーメッセージ -->
                <?php if(!empty($errors)): ?>
                    <div class="error-msg">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="r-block">
                    <div class="first-name">
                        <h4>姓</h4>
                        <input type="text" id="firstName" name="firstName" value="<?php /*echo htmlspecialchars($fistName, ENT_QUOTES, 'UTF-8');*/ ?>" placeholder="姓" maxlength="15" required>
                    </div>
                    <div class="last-name">
                        <h4>姓</h4>
                        <input type="text" id="lastName" name="lastName" value="<?php /*echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8');*/ ?>" placeholder="姓" maxlength="15" required>
                    </div>
                </div>

                <h4>メールアドレス</h4>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="angelo@example.com" required>

                <h4>パスワード</h4>
                <input type="password" id="password" name="password" placeholder="パスワード(8文字以上)" minlength="8" required>

                <input type="submit" id="register" name="register" value="登録">
            </form>
            <!-- アカウント登録画面へ -->
            <p>アカウントをお持ちですか？</p>
            <a href="login.php">ログイン</a> 
        </div>
    </main>
</body>
</html>