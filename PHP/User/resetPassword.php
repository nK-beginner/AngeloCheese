




<?php
    session_start();
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errors[] = 'CSRFトークン不一致エラー';
        }

        $password = $_POST['password'] ?? '';
        $re_password = $_POST['re-password'] ?? '';
        $errors = [];

        // パスワード入力確認
        if($password !== $re_password) {
            $errors[] = 'パスワードが一致しません。';
        } 
        
        // パスワードフォーマットチェック
        if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        // エラーがなければ更新処理へ
        if(empty($errors)) {
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ユーザーIDはセッションから取得(既にセッションにユーザーIDがあること前提)
           $user_id = $_SESSION['user_id'] ?? null; 

            if($user_id) {
                $stmt = $pdo -> prepare('update test_users set password = :password where id = :id');
                $stmt -> bindValue(':password', $hashed_password, PDO::PARAM_STR);
                $stmt -> bindValue(':id', $user_id, PDO::PARAM_INT);
                $stmt -> execute();
                
                header('Location: resetPasswordDone.php');
                exit;

            } else {
                $errors[] = 'ユーザーIDが見つかりません。';
            }
        }

        // エラーあればセッションに残してリダイレクト
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors; // セッションにエラー情報を格納
            header('Location: resetPassword.php'); // エラーがあればリダイレクト
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードのリセット</title>

    <!-- headタグ -->
    <?php include __DIR__.'/../common/headTags.php'; ?>

    <!-- パスワードリセット用CSS -->
    <link rel="stylesheet" href="../css/resetPassword.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include __DIR__.'/../common/header.php'; ?>

    <main>
        <div class="main-container">
            <form action="resetPassword.php" method="POST" class="form">
                <h2><span>R</span>eset <span>P</span>assword<span>.</span></h2>
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- エラーメッセージ表示 -->
                <?php if(!empty($errors)): ?>
                    <?php foreach($errors as $error): ?>
                        <div class="help-link incorrect-pw">
                            <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- パスワード -->
                <label for="password">新しいパスワードを入力してください。</label>
                <input type="password" class="input password" id="password" name="password" placeholder="パスワードを入力してください。" required>

                <!-- 確認用パスワード -->
                <label for="re-password">確認用：再度入力してください。</label>
                <input type="password" class="input password" id="re-password" name="re-password" placeholder="パスワードを再入力してください。" required>

                <!-- 再設定ボタン -->
                <input type="submit" class="input btn" id="reset-pw" name="reset-pw" value="パスワードを再設定する">
            </form>
        </div>
    </main>

    <?php include __DIR__.'/../common/footer.php'; ?>

</body>
</html>