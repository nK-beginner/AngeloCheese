<?php
    session_start();
    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    // データ取得・表示
    try {
        $stmt = $pdo2 -> prepare("SELECT * FROM products");
        $stmt -> execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $errors[] = 'データベース接続エラー:' . $e -> getMessage();
    }

    // データ削除
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errors[] = 'CSRFトークン不一致エラー';
            
        } else {
            // CSRFトークン再生成
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));            
        }

        // フォームデータ取得
        $deletingItemsId = $_POST['delete'] ?? [];

        // 削除
        try {
            $pdo2 -> beginTransaction();

            if(!empty($deletingItemsId)) {
                 // プレースホルダー（？）を$deletingItemsIdの個数分作成 → 削除対象のID数が変わっても対応する
                $id = implode(',', array_fill(0, count($deletingItemsId), '?'));
                $stmt = $pdo2 -> prepare("UPDATE products SET hidden_at = NOW() WHERE id IN ($id)");

                foreach($deletingItemsId as $index => $id) {
                    $stmt -> bindValue($index + 1, (int)$id, PDO::PARAM_INT);
                }
                $stmt -> execute();
                $pdo2 -> commit();

                header('Location: itemDelete.php');
                exit();
            }

        } catch(PDOException $e) {
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());
            $errors[] = 'データベース接続エラーが発生しました。管理者にお問い合わせください';
        }

    }

?>