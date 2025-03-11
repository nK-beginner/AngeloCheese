




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
    
    $errors   = $_SESSION['errors'] ?? [];
    $firstName = $_SESSION['old_firstName'] ?? '';
    $lastName  = $_SESSION['old_lastName'] ?? '';
    $email    = $_SESSION['old_email'] ?? '';
    
    unset($_SESSION['errors'], $_SESSION['old_firstName'], $_SESSION['old_lastName'], $_SESSION['old_email']); // 一度表示したら削除

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = []; // 入力エラー用配列

        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $errors[] = 'CSRFトークン不一致エラー';
        }

        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // フォームデータ取得とサニタイズ
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName'] ?? '');
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
        $stmt = $pdo -> prepare("SELECT id FROM test_users WHERE email = :email LIMIT 1");
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
            $_SESSION['old_firstName'] = $firstName;
            $_SESSION['old_lastName'] = $lastName;
            $_SESSION['old_email'] = $email;
            header('Location: Register.php');
            exit;
        }
        
        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ユーザー登録処理
        $stmt = $pdo -> prepare("INSERT INTO test_users (firstName, lastName, email, password, created_at, updated_at)
                                                 VALUES(:firstName, :lastName, :email, :password, now(), now())");
        $stmt -> bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $stmt -> bindValue(':lastName',  $lastName,  PDO::PARAM_STR);
        $stmt -> bindValue(':email',     $email,     PDO::PARAM_STR);
        $stmt -> bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
        $stmt -> execute();

        // セッション固定攻撃対策
        session_regenerate_id(true);

        // 登録後に自動ログイン
        $_SESSION['user_id']   = $pdo -> lastInsertId();
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName']  = $lastName;
        $_SESSION['email']     = $email;

        header('Location: onlineShop.php');
        exit;
    }
?>


<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>

    <!-- 新規登録用 -->
    <link rel="stylesheet" href="../css/register.css?v=<?php echo time(); ?>">

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="grid-container">
            <div class="welcome-container">
                <img src="/../AngeloCheese/images/AngeloCheese_logo1.png" alt="ロゴ">
                <div class="sub-container">
                    <h1>ようこそ。</h1>
                </div>
            </div>

            <div class="main-container">
                <form action="Register.php" method="POST">
                    <!-- CSRFトークン -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                    <h2 class="page-title"><span>R</span>egister<span>.</span></h2>

                    <?php if(!empty($errors)): ?>
                        <div class="error-msg">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h4>姓</h4>
                    <input class="user-input" type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="山田" maxlength="15" required>

                    <h4>姓</h4>
                    <input class="user-input" type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="花子" maxlength="15" required>

                    <h4>メールアドレス</h4>
                    <input class="user-input" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="angelo@example.com" required>

                    <h4>パスワード</h4>
                    <input class="user-input" type="password" id="password" name="password" placeholder="パスワード(8文字以上)" minlength="8" required>

                    <input class="submit-btn" type="submit" id="register" name="register" value="登録">
                </form>
                <!-- アカウント登録画面へ -->
                <p class="info">アカウントをお持ちですか？</p>
                <a class="log-reg-btn" href="Login.php">ログイン</a> 
            </div>
        </div>

    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>
</body>
</html>