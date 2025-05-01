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

    // 商品情報更新
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errors[] = "不正なアクセスです。";
        }

        $thumbnail          = $_FILES['image'] ?? null;
        $subImages          = $_FILES['images'] ?? null;

        $itemId             = (int)$_POST['item_id'];
        $name               = trim($_POST['productName'] ?? '');
        $description        = trim($_POST['description'] ?? '');
        $categoryId         = (int)($_POST['category'] ?? 0);
        $categoryMap        = [
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

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
    
            echo './itemEdit.php';
            exit;
        }

        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $productData = [
            'itemId'             => $itemId,
            'productName'        => $name,
            'description'        => $description,
            'categoryId'         => $categoryId,
            'categoryName'       => $categoryName,
            'keyword'            => $keyword,
            'size1'              => $size1,
            'size2'              => $size2,
            'taxRate'            => $taxRate,
            'price'              => $price,
            'taxIncludedPrice'   => $taxIncludedPrice,
            'cost'               => $cost,
            'expirationDateMin1' => $expirationDateMin1,
            'expirationDateMax1' => $expirationDateMax1,
            'expirationDateMin2' => $expirationDateMin2,
            'expirationDateMax2' => $expirationDateMax2,
            'hiddenAt'           => $hiddenAt,
        ];

        $uploadDir = '../uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedExt = ["jpg", "jpeg", "png"];

        $pdo2 -> beginTransaction();
        try {
            fncUpdateProduct($pdo2, $productData);
            fncUpdateImage($pdo2, $thumbnail, 1, $uploadDir, $allowedExt, $errors, $itemId);

            $pdo2 -> commit();

            echo './itemEditList.php';
            exit;

        } catch(PDOException $e){
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';

            echo './itemEditList.php';
            exit;
        }
    }
?>