<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require_once __DIR__.'/../../Core/head_tags.php'; ?>
    <title>顧客一覧</title>
    <link rel="stylesheet" href="/../Test/Public/CSS/allUsers.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../Test/Public/CSS/table.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="grid-container">
        <?php include 'sidebar.php'; ?>

        <main>
            <h1>顧客一覧</h1>
            <form action="itemDelete.php" method="POST">
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
                        <?php for($i = 0; $i < count($customers); $i++): 
                            $isDeleted = !is_null($customers[$i]['deleted_at']);
                        ?>
                        
                        <tr style="color: <?php echo $isDeleted ? 'red' : 'inherit'; ?>;">
                            <td><?php echo htmlspecialchars($customers[$i]['id'],         ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($customers[$i]['firstName'].$customers[$i]['lastName'],   ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($customers[$i]['email'],      ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($customers[$i]['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($customers[$i]['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($customers[$i]['deleted_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php endfor; ?>
                    </table>
                </div>
            
            </form>

        </main>
    </div>
    <script type="module" src="/../Test/Public/JS/sidebar.js"></script>
</body>
</html>