




<?php
    session_start();
    require_once __DIR__.'/../Backend/connection.php';
    require_once __DIR__.'/../Backend/csrf_token.php';
    require_once __DIR__.'/../Backend/config.php';

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errors[] = 'CSRFトークン不一致エラー';

        } else {
            // CSRFトークン再生成
            unset($_SESSION['csrf_token']);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));            
        }

        // フォームデータ取得とサニタイズ
        // 詳細
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
        if(empty($name))     {  $errors[] = '商品名が入力されていません。'; }
        if(empty($category_id)) {  $errors[] = 'カテゴリーが選択されていません。'; }
        if(!is_numeric($size1) || $size1 <= 0) {  $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
        if(!is_numeric($size2) || $size2 <= 0) {  $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
        if(!is_numeric($price) || $price <= 0) {  $errors[] = '値段には0より大きい数値を入力してください。';  }
        if(!is_numeric($cost) || $cost <= 0)   {  $errors[] = '原価には0より大きい数値を入力してください。';  }
        if($expirationDateMin1 > $expirationDateMax1) {  $errors[] = '消費期限の大小関係が不正です。';  }
        if($expirationDateMin2 > $expirationDateMax2) {  $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

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
        try {
            $pdo2 -> beginTransaction();

            // productsテーブルに保存
            $stmt = $pdo2 -> prepare('insert into products (name,  description,  category_id, category_name,   keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationDate_min1,  expirationDate_max1,  expirationDate_min2,  expirationDate_max2)
                                                   values (:name, :description, :category_id, :category_name, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationDate_min1, :expirationDate_max1, :expirationDate_min2, :expirationDate_max2)');
            $stmt -> bindValue(':name'               , $name,               PDO::PARAM_STR);
            $stmt -> bindValue(':description'        , $description,        PDO::PARAM_STR);
            $stmt -> bindValue(':category_id'        , $category_id,        PDO::PARAM_INT);
            $stmt -> bindValue(':category_name'      , $category_name,      PDO::PARAM_STR);
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
            $stmt -> execute();

            // 保存した商品のIDを取得
            $product_id = $pdo2 -> lastInsertId();

            // 画像保存の関数
            function fncSaveImage($file, $is_main, $uploadDir, $allowedExt, &$errors, $pdo2, $product_id) {
                if($file && $file['error'] === UPLOAD_ERR_OK) {
                    // 拡張子を取得＆チェック
                    $fineName = basename($file['name']);
                    $fileExt = strtolower(pathinfo($fineName, PATHINFO_EXTENSION));

                    if(!in_array($fileExt, $allowedExt)) {
                        $errors[] = '許可されていないファイル形式です。';
                        return;
                    }

                    // ファイル名のユニーク化
                    $newFileName = uniqid().bin2hex(random_bytes(32)).'.'.$fileExt;
                    $uploadFilePath = $uploadDir.$newFileName;

                    // 画像を保存
                    if(move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                        $stmt = $pdo2 -> prepare("INSERT INTO product_images (product_id,  image_path,  is_main)
                                                                      VALUES (:product_id, :image_path, :is_main)");
                        $stmt -> bindValue(':product_id', $product_id,     PDO::PARAM_INT);
                        $stmt -> bindValue(':image_path', $uploadFilePath, PDO::PARAM_STR);
                        $stmt -> bindValue(':is_main',    $is_main,        PDO::PARAM_INT);
                        $stmt -> execute();
                    } else {
                        $errors[] = '画像の保存に失敗しました。';
                    }
                }
            }
            // メイン画像保存(is_main = 1)
            fncSaveImage($thumbnail, 1, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);

            // サブ画像の保存(is_main = null)
            foreach($files as $file) {
                fncSaveImage($file, null, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);
            }

            // エラー無ければコミット、あればロールバック
            if(empty($errors)) {
                $pdo2 -> commit();
                $_SESSION['success'] = '商品が登録されました。';

            } else {
                $pdo2 -> rollback();
                $_SESSION['errors'] = $errors;
            }

        } catch(Exception $e) {
            $pdo2 -> rollback();
            error_log('データベース接続エラー:' . $e -> getMessage());
            $errors[] = 'データベース接続エラーが発生しました。管理者にお問い合わせください';
        }

        // セッション固定攻撃対策
        session_regenerate_id(true);

        header('Location: itemAdd.php');
        exit;
    }

?>