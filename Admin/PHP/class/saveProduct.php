<?php
    class SaveProduct {
        private $pdo;
        
        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        /*======================================================*/
        /* 用途：商品の保存                            		     */
        /* 引数：各データ                                        */
        /* 戻り値：なし   									     */
        /* 備考：なし											 */
        /*======================================================*/
        public function saveProduct($name, $description, $category_id, $category_name, $keyword, $size1, $size2, $taxRate, $price, $taxIncludedPrice, $cost, $expirationDateMin1, $expirationDateMax1, $expirationDateMin2, $expirationDateMax2) {
            $stmt = $this -> pdo -> prepare('INSERT INTO products (name,  description,  category_id, category_name,   keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationDate_min1,  expirationDate_max1,  expirationDate_min2,  expirationDate_max2)
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
                $isMain：メイン画像かサブか（1またはnull）
                $uploadFir：アップロード先
                $allowedExt：許可する拡張子
                $errors：エラー
                $pdo：DB接続
                $productId：商品ID                               */
        /* 戻り値：SQL実行結果									  */
        /* 備考：なし											 */
        /*======================================================*/
        public function saveProductImage($file, $isMain, $uploadDir, $allowedExt, &$errors, $productId) {
            if($file && $file['error'] === UPLOAD_ERR_OK) {
                // 拡張子を取得＆チェック
                $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
                $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
                if(!in_array($fileExt, $allowedExt)) {
                    $errors[] = '許可されていないファイル形式です。';
                    return;
                }
    
                // ファイル名のユニーク化
                $newFileName = uniqid().bin2hex(random_bytes(32)).'.'.$fileExt;
                $uploadFilePath = $uploadDir.$newFileName;
    
                // 画像を保存
                if(move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                    $stmt = $this -> pdo -> prepare("INSERT INTO product_images (product_id,  image_path,  is_main)
                                                                         VALUES (:product_id, :image_path, :is_main)");
                    $stmt -> bindValue(':product_id', $productId,      PDO::PARAM_INT);
                    $stmt -> bindValue(':image_path', $uploadFilePath, PDO::PARAM_STR);
                    $stmt -> bindValue(':is_main',    $isMain,         PDO::PARAM_INT);
                    $stmt -> execute();
                    
                } else {
                    $errors[] = '画像の保存に失敗しました。';
                    return;
                }
            }
        }
    }
?>