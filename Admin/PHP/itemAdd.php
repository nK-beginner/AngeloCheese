<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        // fncCheckCSRF();
        
        // 画像データ
        $thumbnail          = $_FILES['image'] ?? null;
        $subImages          = $_FILES['images'] ?? null;

        // 文字データ
        $name               = trim($_POST['name'] ?? '');
        $description        = trim($_POST['description'] ?? '');
        $categoryId         = (int)($_POST['category'] ?? 0);
        $categoryMap        = [
            1  => '人気商品',
            2  => 'チーズケーキサンド',
            3  => 'アンジェロチーズ',
            99 => 'その他',
        ];
        $categoryName       = $categoryMap[$categoryId];
        $keyword            = trim($_POST['keyword'] ?? '');
        $size1              = (int)($_POST['size1'] ?? 0);
        $size2              = (int)($_POST['size2'] ?? 0);
        $taxRate            = (float)($_POST['tax-rate'] ?? 0.1);
        $price              = (int)($_POST['price'] ?? 0);
        $cost               = (int)($_POST['cost'] ?? 0);
        $taxIncludedPrice   = (int)($_POST['tax-included-price'] ?? 0);
        $expirationDateMin1 = (int)($_POST['expiration-date-min1'] ?? 0);
        $expirationDateMax1 = (int)($_POST['expiration-date-max1'] ?? 0);
        $expirationDateMin2 = (int)($_POST['expiration-date-min2'] ?? 0);
        $expirationDateMax2 = (int)($_POST['expiration-date-max2'] ?? 0);
        
        // 各入力バリデーション
        if(empty($name))       {                        $errors[] = '商品名が入力されていません。'; }
        if(empty($categoryId)) {                        $errors[] = 'カテゴリーが選択されていません。'; }
        if(!is_numeric($size1) || $size1 <= 0) {        $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
        if(!is_numeric($size2) || $size2 <= 0) {        $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
        if(!is_numeric($price) || $price <= 0) {        $errors[] = '値段には0より大きい数値を入力してください。';  }
        if(!is_numeric($cost)  || $cost  <= 0) {        $errors[] = '原価には0より大きい数値を入力してください。';  }
        if($expirationDateMin1 > $expirationDateMax1) { $errors[] = '消費期限の大小関係が不正です。';  }
        if($expirationDateMin2 > $expirationDateMax2) { $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
    
            header("Location: itemAdd.php");
            exit;
        }

        $uploadDir = '../uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 許可する拡張子
        $allowedExt    = ["jpg", "jpeg", "png"];
        $uploadedPaths = [];

        // 保存トランザクション
        $pdo2 -> beginTransaction();
        try {
            // productsへ保存
            fncSaveProduct($pdo2, $name, $description, $categoryId, $categoryName, $keyword, $size1, $size2, $taxRate, $price, $taxIncludedPrice, $cost, $expirationDateMin1, $expirationDateMax1, $expirationDateMin2, $expirationDateMax2);

            // 保存した商品のID取得
            $productId = $pdo2 -> lastInsertId();

            // メイン画像保存
            fncSaveImage($pdo2, $thumbnail, 1, $uploadDir, $allowedExt, $errors, $productId);

            // サブ画像保存
            foreach($subImages['name'] as $index => $name) {
                $file = [
                    'name'     => $subImages['name'][$index],
                    'type'     => $subImages['type'][$index],
                    'tmp_name' => $subImages['tmp_name'][$index],
                    'error'    => $subImages['error'][$index],
                    'size'     => $subImages['size'][$index],
                ];

                fncSaveImage($pdo2, $file, null, $uploadDir, $allowedExt, $errors, $productId);
            }

            $pdo2 -> commit();

            echo './Admin/View/itemAdd.php';
            exit;
            // die('done');

        } catch(PDOException $e) {
            die('no');
            $pdo2 -> rollBack();
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }
    }
?>