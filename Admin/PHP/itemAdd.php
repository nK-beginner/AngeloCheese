<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';
    require_once __DIR__.'/../PHP/function/functions.php';
    require_once __DIR__.'/../PHP/function/dataControl.php';

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

        // フォームデータ取得とサニタイズ
        $name               = trim($_POST['product-name'] ?? '');
        $description        = trim($_POST['product-description'] ?? '');
        $category_id        = (int)($_POST['product-category'] ?? 0);

        $category_map       = [
            1 => '人気商品',
            2 => 'チーズケーキサンド',
            3 => 'アンジェロチーズ',
            99 => 'その他',
        ];
        $category_name      = $category_map[$category_id];

        $keyword            = trim($_POST['keyword'] ?? '');
        $size1              = (int)($_POST['size1'] ?? 0);
        $size2              = (int)($_POST['size2'] ?? 0);
        $taxRate            = (float)($_POST['tax-rate'] ?? 0.1);
        $price              = (int)($_POST['price'] ?? 0);
        $taxIncludedPrice   = (int)($_POST['tax-included-price'] ?? 0);
        $cost               = (int)($_POST['cost'] ?? 0);
        $expirationDateMin1 = (int)($_POST['expirationDate-min1'] ?? 0);
        $expirationDateMax1 = (int)($_POST['expirationDate-max1'] ?? 0);
        $expirationDateMin2 = (int)($_POST['expirationDate-min2'] ?? 0);
        $expirationDateMax2 = (int)($_POST['expirationDate-max2'] ?? 0);

        // 画像
        $thumbnail = $_FILES['thumbnail'] ?? null;
        $files     = [
            $_FILES['file1'] ?? null,
            $_FILES['file2'] ?? null,
            $_FILES['file3'] ?? null,
            $_FILES['file4'] ?? null,
            $_FILES['file5'] ?? null,
        ];

        // 各入力バリデーション
        if(empty($name))     {                           $errors[] = '商品名が入力されていません。'; }
        if(empty($category_id)) {                        $errors[] = 'カテゴリーが選択されていません。'; }
        if(!is_numeric($size1) || $size1 <= 0) {         $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
        if(!is_numeric($size2) || $size2 <= 0) {         $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
        if(!is_numeric($price) || $price <= 0) {         $errors[] = '値段には0より大きい数値を入力してください。';  }
        if(!is_numeric($cost) || $cost <= 0)   {         $errors[] = '原価には0より大きい数値を入力してください。';  }
        if($expirationDateMin1 > $expirationDateMax1) {  $errors[] = '消費期限の大小関係が不正です。';  }
        if($expirationDateMin2 > $expirationDateMax2) {  $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
    
            header("Location: itemAdd.php");
            exit;
        }

        /******************** ↓ 画像の保存前処理 ↓ ********************/
        // アップロードディレクトリ設定(無ければ作成)
        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 755, true);
        }

        // 許可する拡張子
        $allowedExt = ["jpg", "jpeg", "png"];
        $uploadedPaths = [];

        // 保存トランザクション
        $pdo2 -> beginTransaction();
        try {
            // productsテーブルに保存
            fncSaveProduct($pdo2, $name, $description, $category_id, $category_name, $keyword, $size1, $size2, $taxRate, $price, $taxIncludedPrice, $cost, $expirationDateMin1, $expirationDateMax1, $expirationDateMin2, $expirationDateMax2);

            // 保存した商品のIDを取得
            $product_id = $pdo2 -> lastInsertId();

            // メイン画像保存(is_main = 1)
            fncSaveImage($thumbnail, 1, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);

            // サブ画像の保存(is_main = null)
            foreach($files as $file) {
                fncSaveImage($file, null, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);
            }

            $pdo2 -> commit();

        } catch(Exception $e) {
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }

        // セッション固定攻撃対策
        session_regenerate_id(true);

        header('Location: itemAdd.php');
        exit;
    }

?>