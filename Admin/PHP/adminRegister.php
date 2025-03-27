




<?php
    session_start();
    require_once __DIR__ . '/Backend/connection.php';
    require_once __DIR__ . '/Backend/csrf_token.php';

    $errors      = $_SESSION['errors'] ?? [];
    $firstName   = $_SESSION['old-firstName'] ?? ''; // エラー時に苗字を保持
    $lastName    = $_SESSION['old-lastName'] ?? '';  // エラー時に名前を保持
    $email       = $_SESSION['old-email'] ?? '';     // エラー時にメールを保持

    unset($_SESSION['errors'], $_SESSION['old-firstName'], $_SESSION['old-lastName']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // 受け取り
        $firstName = trim($_POST['first-name'] ?? '');
        $lastName  = trim($_POST['last-name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = trim($_POST['password'] ?? '');

        // メアドチェック
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '有効なメールアドレスを入力してください。';
        }

        // パスワードのフォーマット設定
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        try {
            $stmt = $pdo2 -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
            $stmt -> execute();
            $admin = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($admin) {
                $errors[] = 'このメールアドレスは既に登録されています。';
            }

            if(!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old-firstName'] = $firstName;
                $_SESSION['old-lastName'] = $lastName;
                $_SESSION['old-email'] = $email;
                header('Location: adminRegister.php');
                exit;
            }

            // パスワードハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 登録
            $stmt = $pdo2 -> prepare("INSERT INTO admin (firstName, lastName, email, password)
                                                values (:firstName, :lastName, :email, :password)");
            $stmt -> bindValue(":firstName", $firstName     , PDO::PARAM_STR);
            $stmt -> bindValue(":lastName",  $lastName      , PDO::PARAM_STR);
            $stmt -> bindValue(":email",     $email         , PDO::PARAM_STR);
            $stmt -> bindValue(":password",  $hashedPassword, PDO::PARAM_STR);
            $stmt -> execute();

            // セッション固定攻撃対策
            session_regenerate_id(true);

            header('Location: adminLogin.php');
            exit;

        } catch(PDOException $e) {
            $errors[] = 'データベース接続エラー';
            
        }

    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者用アカウント登録画面</title>

    <!-- ページアイコン -->
    <link rel="icon" href="../Admin/images/AngeloCheese_face.png">

    <!-- 管理者登録用CSS -->
    <link rel="stylesheet" href="CSS/adminRegister.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <div class="main-container">
            <form action="adminRegister.php" method="POST" class="register-form">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <img src="images/AngeloCheese_face.png" alt="アンジェロチーズロゴ">
                <h1>アカウント登録</h1>
                
                <!-- エラーメッセージ -->
                <?php if(!empty($errors)): ?>
                    <div class="error-container">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>        
                        <?php endforeach; ?>
                    </div>  
                <?php endif; ?>

                <!-- 氏名 -->
                <div class="name-block">
                    <div class="block">
                        <h3>氏</h3>
                        <input type="text" name="first-name" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="例：山田" maxlength="10">
                    </div>

                    <div class="block">
                        <h3>名</h3>
                        <input type="text" name="last-name" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="例：太郎" maxlength="10">
                    </div>
                </div>
                
                <!-- メアド -->
                <div class="block">
                    <h3>メールアドレス</h3>
                    <input type="text" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="メールアドレスを入力してください。">
                </div>
                
                <!-- パスワード -->
                <div class="block">
                    <h3>パスワード</h3>
                    <input type="password" name="password" placeholder="英数字記号を含む8文字以上で入力してください。" minlength="8">
                </div>
                
                <!-- 送信ボタン -->
                <input type="submit" class="submit-btn" value="登録">
            </form>            
        </div>
    </main>
</body>
</html>