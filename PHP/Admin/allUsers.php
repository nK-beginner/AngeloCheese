




<?php
    session_start();
    require_once __DIR__ . '/../Backend/connection.php';
    require_once __DIR__ . '/Backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    try {
        $stmt = $pdo -> prepare("SELECT * FROM test_users");
        $stmt -> execute();
        $users = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());
        $errors[] = 'データベース接続エラー';
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ヘッダータグ -->
    <?php include 'headTags.php' ?>

    <title>顧客一覧</title>

    <!-- 顧客一覧用CSS -->
    <link rel="stylesheet" href="CSS/allUsers.css?v=<?php echo time(); ?>">

    <!-- 削除画面用CSS流用（テーブルのスタイル） -->
    <link rel="stylesheet" href="CSS/itemDelete.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- 顧客一覧エリア -->
        <main>
            <h1>顧客一覧</h1>
            <form action="itemDelete.php" method="POST">
                <!-- CSRFトークン -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">  
                
                <div class="table-records">
                    <table>
                        <tr>
                            <th>顧客id</th>
                            <th>ユーザー名</th>
                            <th>メールアドレス</th>
                            <th>アカウント作成日</th>
                            <th>アカウント更新日</th>
                            <th>アカウント削除日</th>
                        </tr>
                        <?php for($i = 0; $i < count($users); $i++): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($users[$i]['id'],         ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['username'],   ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['email'],      ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['deleted_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php endfor; ?>
                    </table>
                </div>
            
            </form>

        </main>
    </div>
    <script src="JS/sidebar.js"></script> <!-- サイドバー -->
    <script src="JS/allUsers.js"></script> <!-- 顧客一覧用 -->
</body>
</html>
