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
        $products = fncGetData($pdo2, 2, 1);

    } catch(PDOException $e) {
        error_log('データベース接続エラー:' . $e -> getMessage());

        $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
    }

    // allItems.phpから商品を選択されてきた時
    $editItem = null;

    if(isset($_SESSION['edit_item_id'])) {
        $editItemId = $_SESSION['edit_item_id'];
        unset($_SESSION['edit_item_id']);
        
        try {
            $editItem = fncUpdatingProduct($pdo2, $editItemId);

        } catch(PDOException $e) {
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }
    }

    // 商品情報更新
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        fncCheckCSRF();

        $pdo2 -> beginTransaction();
        try {
            $itemId             = (int)$_POST['item_id'];
            $name               = $_POST['product-name'];
            $description        = $_POST['product-description'];
            $categoryId         = (int)$_POST['product-category'];

            $categoryMap       = [
                1 => '人気商品',
                2 => 'チーズケーキサンド',
                3 => 'アンジェロチーズ',
                99 => 'その他',
            ];
            
            $categoryName       = $categoryMap[$categoryId];
            $keyword            = $_POST['keyword'];
            $size1              = (int)$_POST['size1'];
            $size2              = (int)$_POST['size2'];
            $taxRate            = (float)$_POST['tax-rate'];
            $price              = (int)$_POST['price'];
            $taxIncludedPrice   = (int)$_POST['tax-included-price'];
            $cost               = (int)$_POST['cost'];
            $expirationDateMin1 = (int)$_POST['expirationDate-min1'];
            $expirationDateMax1 = (int)$_POST['expirationDate-max1'];
            $expirationDateMin2 = (int)$_POST['expirationDate-min2'];
            $expirationDateMax2 = (int)$_POST['expirationDate-max2'];
            $hiddenAt           = NULL;
            if( $_POST['display'] === 'off' ) {
                $hiddenAt = "NOW()";
            }

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
                'tax_included_price'  => $taxIncludedPrice,
                'cost'                => $cost,
                'expirationDate_min1' => $expirationDateMin1,
                'expirationDate_max1' => $expirationDateMax1,
                'expirationDate_min2' => $expirationDateMin2,
                'expirationDate_max2' => $expirationDateMax2,
                'hidden_at'           => $hiddenAt,
            ];

            fncUpdateProduct($pdo2, $productData);

            $pdo2 -> commit();
            header('Location: itemEdit.php');

        } catch(PDOException $e){
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }
    }
?>