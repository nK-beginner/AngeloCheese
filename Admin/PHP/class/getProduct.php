<?php
    class GetProduct {
        private $pdo;

        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        /*======================================================*/
        /* 用途：商品情報をDBから取得            		          */
        /* 引数：$itemId：編集する商品のID                        */
        /* 戻り値：更新する商品情報								  */
        /* 備考：なし											 */
        /*======================================================*/
        public function fncGetProduct($itemId) {
            $stmt = $this -> pdo -> prepare(
                "SELECT 
                    id, 
                    name,
                    description,
                    category_id,
                    category_name,
                    keyword,
                    size1,
                    size2,
                    tax_rate,
                    price,
                    tax_included_price,
                    cost,
                    expirationDate_min1,
                    expirationDate_max1,
                    expirationDate_min2,
                    expirationDate_max2
                FROM products
                WHERE id = :id
                ");
            $stmt -> bindValue(':id', $itemId, PDO::PARAM_INT);
            $stmt -> execute();
    
            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }

        /*======================================================*/
        /* 用途：商品情報をDBから取得            		          */
        /* 引数：なし                                            */
        /* 戻り値：全商品       								 */
        /* 備考：なし											 */
        /*======================================================*/
        public function fncGetProductAll() {
            $stmt = $this -> pdo -> prepare(
                "SELECT 
                    id, 
                    name,
                    description,
                    category_id,
                    category_name,
                    keyword,
                    size1,
                    size2,
                    tax_rate,
                    price,
                    tax_included_price,
                    cost,
                    expirationDate_min1,
                    expirationDate_max1,
                    expirationDate_min2,
                    expirationDate_max2,
                    created_at,
                    updated_at,
                    hidden_at
                FROM
                    products
                ");
            $stmt -> execute();
    
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }

        /*======================================================*/
        /* 用途：商品メイン画像をDBから取得            		       */
        /* 引数：$itemId：商品ID                                 */
        /* 戻り値：更新する商品情報								  */
        /* 備考：なし											 */
        /*======================================================*/
        public function fncGetMainImage($itemId) {
            $stmt = $this -> pdo -> prepare(
                "SELECT
                    image_path
                FROM
                    product_images
                WHERE
                    product_id = :id
                AND
                    is_main = 1
                ");
            $stmt -> bindValue(':id', $itemId, PDO::PARAM_INT);
            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }

        /*======================================================*/
        /* 用途：商品サブ画像をDBから取得            		       */
        /* 引数：$itemId：商品ID                                 */
        /* 戻り値：更新する商品情報								  */
        /* 備考：なし											 */
        /*======================================================*/
        public function fncGetSubImages($itemId) {
            $stmt = $this -> pdo -> prepare(
                "SELECT
                    image_path
                FROM
                    product_images
                WHERE
                    product_id = :id
                AND
                    is_main IS NULL
                ");
            $stmt -> bindValue(':id', $itemId, PDO::PARAM_INT);
            $stmt -> execute();

            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>