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

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $_SESSION['edit_item_id'] = intval($_POST['id']);

        header('Location: itemEdit.php');
        exit();
    }

    // 一覧表示
    try {
        $products = fncGetData($pdo2, 1, 1);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());

        $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
    }

    // allItems.phpから商品を選択されてきた時
    $editItem  = null;
    $subImages = [];

    if(isset($_SESSION['edit_item_id'])) {
        $editItemId = $_SESSION['edit_item_id'];
        unset($_SESSION['edit_item_id']);
        
        try {
            $editItem  = fncGetProduct($pdo2, $editItemId);
            $subImages = fncGetSubImages($pdo2, $editItemId); 

        } catch(PDOException $e) {
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }
    }

    // 商品情報更新
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

        $itemId             = (int)$_POST['item_id'];
        $name               = trim($_POST['name'] ?? '');
        $description        = trim($_POST['description'] ?? '');
        $categoryId         = (int)($_POST['category'] ?? 0);

        $categoryMap       = [
            1 => '人気商品',
            2 => 'チーズケーキサンド',
            3 => 'アンジェロチーズ',
            99 => 'その他',
        ];
        
        $categoryName       = $categoryMap[$categoryId];
        $keyword            = trim($_POST['keyword'] ?? '');
        $size1              = (int)($_POST['size1'] ?? 0);
        $size2              = (int)($_POST['size2'] ?? 0);
        $taxRate            = (float)($_POST['tax-rate'] ?? 0.1);
        $price              = (int)str_replace(',', '', $_POST['price']);
        $taxIncludedPrice   = (int)str_replace(',', '', $_POST['tax-included-price']);
        $cost               = (int)str_replace(',', '', $_POST['cost']);
        $expirationDateMin1 = (int)($_POST['expiration-date-min1'] ?? 0);
        $expirationDateMax1 = (int)($_POST['expiration-date-max1'] ?? 0);
        $expirationDateMin2 = (int)($_POST['expiration-date-min2'] ?? 0);
        $expirationDateMax2 = (int)($_POST['expiration-date-max2'] ?? 0);
        $hiddenAt           = $_POST['display'] === 'off' ? "NOW()" : NULL;

        if(empty($name))       {                        $errors[] = '商品名が入力されていません。'; }
        if(empty($categoryId)) {                        $errors[] = 'カテゴリーが選択されていません。'; }
        if(!is_numeric($size1) || $size1 <= 0) {        $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
        if(!is_numeric($size2) || $size2 <= 0) {        $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
        if(!is_numeric($price) || $price <= 0) {        $errors[] = '値段には0より大きい数値を入力してください。';  }
        if(!is_numeric($cost)  || $cost  <= 0) {        $errors[] = '原価には0より大きい数値を入力してください。';  }
        if($expirationDateMin1 > $expirationDateMax1) { $errors[] = '消費期限の大小関係が不正です。';  }
        if($expirationDateMin2 > $expirationDateMax2) { $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

        $productData = [
            'id'                  => $itemId,
            'name'                => $name,
            'description'         => $description,
            'category_id'         => $categoryId,
            'category_name'       => $categoryName,
            'keyword'             => $keyword,
            'size1'               => $size1,
            'size2'               => $size2,
            'tax_rate'            => $taxRate,
            'price'               => $price,
            'tax-included-price'  => $taxIncludedPrice,
            'cost'                => $cost,
            'expirationDate_min1' => $expirationDateMin1,
            'expirationDate_max1' => $expirationDateMax1,
            'expirationDate_min2' => $expirationDateMin2,
            'expirationDate_max2' => $expirationDateMax2,
            'hidden_at'           => $hiddenAt,
        ];

        $pdo2 -> beginTransaction();
        try {
            fncUpdateProduct($pdo2, $productData);

            $pdo2 -> commit();
            header('Location: itemEdit.php');

        } catch(PDOException $e){
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
            header("Location: itemEdit.php");
            exit;
        }
    }
?>