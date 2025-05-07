<?php
class UpdateProduct {
    private $pdo;

    public function __construct($pdo) {
        $this -> pdo = $pdo;
    }

	/*======================================================*/
    /* 用途：商品情報の更新                 		          */
	/* 引数：各種データ                                       */
	/* 戻り値：なし     									  */
	/* 備考：なし											 */
	/*======================================================*/
    public function updateProduct(array $productData) {
        $stmt = $this -> pdo-> prepare(
            "UPDATE products 
            SET 
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
                expirationDate_min1 = :expirationDate_min1, 
                expirationDate_max1 = :expirationDate_max1,
                expirationDate_min2 = :expirationDate_min2,
                expirationDate_max2 = :expirationDate_max2, 
                --hidden_at = " . ($productData['hiddenAt'] ? "NOW()" : "NULL") . " 
                hidden_at = :hidden_at
            WHERE id = :id
            ");
        $stmt -> bindValue(':id'                 , $productData['itemId'],             PDO::PARAM_INT);                                    
        $stmt -> bindValue(':name'               , $productData['productName'],        PDO::PARAM_STR);
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

        $hiddenAtValue = $productData['hiddenAt'] ? date('Y-m-d H:i:s') : null;
        $stmt -> bindValue(':hidden_at', $hiddenAtValue, $hiddenAtValue ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt -> execute();
    }

	/*======================================================*/
	/* 用途：画像更新処理                           		  */
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
    function fncUpdateImage($file, $isMain, $uploadDir, $allowedExt, &$errors, $productId) {
        if($file && $file['error'] === UPLOAD_ERR_OK) {
            $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
            if(!in_array($fileExt, $allowedExt)) {
                $errors[] = '許可されていないファイル形式です。';
                return;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowedMime = ['image/jpeg', 'image/png']; // 必要に応じて拡張
            
            if (!in_array($mime, $allowedMime)) {
                $errors[] = '許可されていないMIMEタイプの画像です。';
                return;
            }
    
            /********** 同一画像名があれば保存阻止＆置き換え **********/
            $fileHash = hash_file('sha256', $file['tmp_name']);
    
            $existingFilePath = null;
            foreach(glob($uploadDir . '*.' . $fileExt) as $existingFile) {
                if(hash_file('sha256', $existingFile) === $fileHash) {
                    $existingFilePath = $existingFile;
                    break;
                }
            }
            
            if($existingFilePath) {
                $uploadFilePath = $existingFilePath;
                
            } else {
                $newFileName = uniqid() . bin2hex(random_bytes(32)) . '.' . $fileExt;
                $uploadFilePath = $uploadDir . $newFileName;
    
                if(!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                    $errors[] = '画像のアップロードに失敗しました。';
                    return;
                }
            }
            /************************* END *************************/
    
            if($isMain === 1) {
                $stmt = $this -> pdo -> prepare(
                    "UPDATE product_images
                     SET image_path = :image_path 
                     WHERE product_id = :id
                     AND is_main = 1");
                $stmt -> bindValue(':image_path', $uploadFilePath, PDO::PARAM_STR);
                $stmt -> bindValue(':id',         $productId,      PDO::PARAM_STR);
    
                $stmt -> execute();
            }
        }
    }
}


?>