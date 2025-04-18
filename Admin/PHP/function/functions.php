<?php
	/*======================================================*/
	/* 用途：CSRFトークンチェック               			  */
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncCheckCSRF() {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

	/*======================================================*/
	/* 用途：登録・ログイン試行回数監視						   */
	/* 引数：$key：failed_login_またはfailed_register_, $limit：ログイン回数, $lockTime：ロック時間, $isFailed：失敗したかどうか */
	/* 戻り値：失敗時・試行回数オーバー時にtrue				   */
	/* 備考：なし											 */
	/*======================================================*/
    function fncManageAttempts($key, $limit = 5, $lockTime = 900, $isFailed = false) {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'last_attempt' => 0];
        }

        // 失敗記録
        if ($isFailed) {
            $_SESSION[$key]['count']++;
            $_SESSION[$key]['last_attempt'] = time();
            return;
        }

        // 試行回数制限チェック
        if ($_SESSION[$key]['count'] >= $limit) {
            if (time() - $_SESSION[$key]['last_attempt'] < $lockTime) {
                return true;

            } else {
                unset($_SESSION[$key]);
            }
        }
        return false;
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続, $email：$_POSTされたemail          */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncGetUserByEmail($pdo, $email) {
        $stmt = $pdo -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
        $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

	/*======================================================*/
	/* 用途：ユーザー登録        							  */
	/* 引数：$pdo：DB接続, 
            $firstName：$_POSTされたfirstName, 
            $lastName：$_POSTされたlastName, 
            $email：$_POSTされたemail, 
            $hashedPassword：ハッシュ化されたpassword　*/
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSaveUser($pdo, $firstName, $lastName, $email, $hashedPassword) {
        $stmt = $pdo -> prepare("INSERT INTO admin (firstName, lastName, email, password)
                                            VALUES (:firstName, :lastName, :email, :password)");
        $stmt -> bindValue(":firstName", $firstName     , PDO::PARAM_STR);
        $stmt -> bindValue(":lastName",  $lastName      , PDO::PARAM_STR);
        $stmt -> bindValue(":email",     $email         , PDO::PARAM_STR);
        $stmt -> bindValue(":password",  $hashedPassword, PDO::PARAM_STR);
        $stmt -> execute();
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続, ($filename：ファイル名、デフォルト指定あり) */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function exportCSV(PDO $pdo, $filename = 'products.csv') {
        $stmt = $pdo -> prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        ob_clean();

        header('Content-Type: text/csv; charset=shift_JIS');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        $headers = [
            '商品ID', '商品名', '商品説明', 'カテゴリー名', 'キーワード', 'サイズ1', 'サイズ2', '税率', '値段', '税込価格', '原価', 
            '消費期限1', '消費期限2', '消費期限(解凍後)1', '消費期限(解凍後)2', '作成日', '更新日', '商品表示状態'
        ];
        fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $headers));

        foreach($products as $product) {
            fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), [
                $product['id'],
                $product['name'],
                $product['description'],
                $product['category_name'],
                $product['keyword'],
                $product['size1'] . 'cm',
                $product['size2'] . 'cm',
                $product['tax_rate'] * 100 . '%',
                '¥' . number_format($product['price']),
                '¥' . number_format($product['tax_included_price']),
                '¥' . number_format($product['cost']),
                $product['expirationdate_min1'] . '日',
                $product['expirationdate_max1'] . '日',
                $product['expirationdate_min2'] . '日',
                $product['expirationdate_max2'] . '日',
                $product['created_at'],
                $product['updated_at'],
                !is_null($product['hidden_at']) ? '非表示中' : '',
            ]));
        }

        fclose($output);
        exit;
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続
            その他：$_POSTされたデータ                        */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSaveProduct($pdo, $name, $description, $category_id, $category_name, $keyword, $size1, $size2, $taxRate, $price, $taxIncludedPrice, $cost, $expirationDateMin1, $expirationDateMax1, $expirationDateMin2, $expirationDateMax2) {
            $stmt = $pdo -> prepare('INSERT INTO products (name,  description,  category_id, category_name,   keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationDate_min1,  expirationDate_max1,  expirationDate_min2,  expirationDate_max2)
                                                   VALUES (:name, :description, :category_id, :category_name, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationDate_min1, :expirationDate_max1, :expirationDate_min2, :expirationDate_max2)');
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
    }

	/*======================================================*/
	/* 用途：画像登録処理                           		  */
	/* 引数：$file：投稿された画像ファイル
            isMain：メイン画像かサブか（1またはnull）
            $uploadFir：アップロード先
            $allowedExt：許可する拡張子
            $errors：エラー
            $pdo：DB接続
            $productId：商品ID                               */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSaveImage($pdo, $file, $isMain, $uploadDir, $allowedExt, $errors, $productId) {
        if($file && $file['error'] === UPLOAD_ERR_OK) {
            // 拡張子を取得＆チェック
            $fineName = basename($file['name']);
            $fileExt  = strtolower(pathinfo($fineName, PATHINFO_EXTENSION));

            if(!in_array($fileExt, $allowedExt)) {
                $errors[] = '許可されていないファイル形式です。';
                return;
            }

            // ファイル名のユニーク化
            $newFileName = uniqid().bin2hex(random_bytes(32)).'.'.$fileExt;
            $uploadFilePath = $uploadDir.$newFileName;

            // 画像を保存
            if(move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                $stmt = $pdo -> prepare("INSERT INTO product_images (product_id,  image_path,  is_main)
                                                             VALUES (:product_id, :image_path, :is_main)");
                $stmt -> bindValue(':product_id', $productId,      PDO::PARAM_INT);
                $stmt -> bindValue(':image_path', $uploadFilePath, PDO::PARAM_STR);
                $stmt -> bindValue(':is_main',    $isMain,         PDO::PARAM_INT);
                $stmt -> execute();
                
            } else {
                $errors[] = '画像の保存に失敗しました。';
            }
        }
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続, $ids：削除（非表示）する商品配列     */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncHideProducts(PDO $pdo, array $deletingItemIds) {
        $placeholders = implode(',', array_fill(0, count($deletingItemIds), '?'));

        $stmt = $pdo -> prepare("UPDATE products SET hidden_at = NOW() WHERE id IN ($placeholders)");

        foreach($deletingItemIds as $index => $id) {
            $stmt -> bindValue($index + 1, (int)$id, PDO::PARAM_INT);
        }
        $stmt -> execute();
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続, $editItemId：編集する商品のID       */
	/* 戻り値：更新する商品情報								   */
	/* 備考：なし											 */
	/*======================================================*/
    function fncUpdatingProduct($pdo, $editItemId) {
        $stmt = $pdo -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id WHERE p.id = :id");
        $stmt -> bindValue(':id', $editItemId, PDO::PARAM_INT);
        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続
            その他：$_POSTされたデータ                        */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncUpdateProduct(PDO $pdo, array $productData) {
        $stmt = $pdo -> prepare("UPDATE products SET 
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
                                            hidden_at = " . ($productData['hiddenAt'] ? "NOW()" : "NULL") . " 
                                            WHERE id = :id");
        $stmt -> bindValue(':name'               , $productData['name'],               PDO::PARAM_STR);
        $stmt -> bindValue(':description'        , $productData['description'],        PDO::PARAM_STR);
        $stmt -> bindValue(':category_id'        , $productData['categoryId'],         PDO::PARAM_INT);
        $stmt -> bindValue(':category_name'      , $productData['categoryName'],       PDO::PARAM_STR);
        $stmt -> bindValue(':keyword'            , $productData['keyword'],            PDO::PARAM_STR);
        $stmt -> bindValue(':size1'              , $productData['size1'],              PDO::PARAM_INT);
        $stmt -> bindValue(':size2'              , $productData['size2'],              PDO::PARAM_INT);
        $stmt -> bindValue(':tax_rate'           , $productData['taxRate'],            PDO::PARAM_STR);
        $stmt -> bindValue(':price'              , $productData['price'],              PDO::PARAM_INT);
        $stmt -> bindValue(':tax_included_price' , $productData['taxIncludedPrice'],   PDO::PARAM_INT);
        $stmt -> bindValue(':cost'               , $productData['cost'],               PDO::PARAM_INT);
        $stmt -> bindValue(':expirationDate_min1', $productData['expirationDateMin1'], PDO::PARAM_INT);
        $stmt -> bindValue(':expirationDate_max1', $productData['expirationDateMax1'], PDO::PARAM_INT);
        $stmt -> bindValue(':expirationDate_min2', $productData['expirationDateMin2'], PDO::PARAM_INT);
        $stmt -> bindValue(':expirationDate_max2', $productData['expirationDateMax2'], PDO::PARAM_INT);
        $stmt -> bindValue(':id'                 , $productData['itemId'],             PDO::PARAM_INT);
        
        return $stmt -> execute();
    }
?>