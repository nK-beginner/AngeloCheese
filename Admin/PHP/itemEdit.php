<?php
    session_start();
    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $_SESSION['edit_item_id'] = intval($_POST['id']);
        header('Location: itemEdit.php');
        exit();
    }

    // 一覧表示
    try {
        $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id");
        $stmt -> execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $errors[] = 'データベース接続エラー:' . $e -> getMessage();
    }

    // allItems.phpから商品を選択されてきた時
    $editItem = null;
    if(isset($_SESSION['edit_item_id'])) {
        $editItemId = $_SESSION['edit_item_id'];
        unset($_SESSION['edit_item_id']);
        try {
            $stmt = $pdo2 -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id WHERE p.id = :id");
            $stmt -> bindValue(':id', $editItemId, PDO::PARAM_INT);
            $stmt -> execute();
            $editItem = $stmt -> fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            $errors[] = 'データベース接続エラー:' . $e -> getMessage();
        }
    }

    // 商品情報更新
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        try {
            $pdo2 -> beginTransaction();

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

            $stmt = $pdo2 -> prepare("UPDATE products SET 
                                                name = :name, 
                                                description = :description,
                                                category_id = :category_id, 
                                                category_name = :category_name, 
                                                keyword = :keyword, 
                                                size1 = :size1, 
                                                size2 = :size2, 
                                                tax_rate = :tax_rate, 
                                                price = :price, 
                                                tax_included_price = :tax_included_price,
                                                cost = :cost, 
                                                expirationdate_min1 = :expirationDate_min1, 
                                                expirationdate_max1 = :expirationDate_max1,
                                                expirationdate_min2 = :expirationDate_min2,
                                                expirationdate_max2 = :expirationDate_max2, 
                                                hidden_at = " . ($hiddenAt ? "NOW()" : "NULL") . " 
                                                WHERE id = :id");
            $stmt -> bindValue(':name'               , $name,               PDO::PARAM_STR);
            $stmt -> bindValue(':description'        , $description,        PDO::PARAM_STR);
            $stmt -> bindValue(':category_id'        , $categoryId,         PDO::PARAM_INT);
            $stmt -> bindValue(':category_name'      , $categoryName,       PDO::PARAM_STR);
            $stmt -> bindValue(':keyword'            , $keyword,            PDO::PARAM_STR);
            $stmt -> bindValue(':size1'              , $size1,              PDO::PARAM_INT);
            $stmt -> bindValue(':size2'              , $size2,              PDO::PARAM_INT);
            $stmt -> bindValue(':tax_rate'           , $taxRate,            PDO::PARAM_STR);
            $stmt -> bindValue(':price'              , $price,              PDO::PARAM_INT);
            $stmt -> bindValue(':tax_included_price' , $taxIncludedPrice,   PDO::PARAM_INT);
            $stmt -> bindValue(':cost'               , $cost,               PDO::PARAM_INT);
            $stmt -> bindValue(':expirationDate_min1', $expirationDateMin1, PDO::PARAM_INT);
            $stmt -> bindValue(':expirationDate_max1', $expirationDateMax1, PDO::PARAM_INT);
            $stmt -> bindValue(':expirationDate_min2', $expirationDateMin2, PDO::PARAM_INT);
            $stmt -> bindValue(':expirationDate_max2', $expirationDateMax2, PDO::PARAM_INT);
            $stmt -> bindValue(':id'                 , $itemId,             PDO::PARAM_INT);
            $stmt -> execute();

            $pdo2 -> commit();
            header('Location: itemEdit.php');

        } catch(PDOException $e){
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());
        }
    }
?>