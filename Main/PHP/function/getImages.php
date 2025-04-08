<?php
	/*======================================================*/
	/* 用途：使用するSQLの操作									                     */
	/* 引数：$pdo：DB接続, $int：switch文に使用, $fetchType：0 = fetch, 1 = fetchAll */
	/* 戻り値：SQL実行結果															*/
	/* 備考：なし																    */
	/*======================================================*/
    function fncGetImages(PDO $pdo, int $int, int $fetchType) {
        switch($int) {
            case 1:
                /********** OnlineShop **********/
                $stmt = $pdo -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price, p.category_id, p.category_name
                    FROM product_images AS pi
                    JOIN products AS p ON pi.product_id = p.id
                    WHERE pi.is_main = 1
                    AND  hidden_at IS NULL
                    ORDER BY p.id
                ");
                break;

            case 2:
                /********** Product : メイン画像用 + 商品名などもここから取ること **********/
                $stmt = $pdo -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON pi.product_id = p.id WHERE p.id = :id AND pi.is_main = 1");
                $stmt -> bindValue(":id", $_SESSION['productId'], PDO::PARAM_INT);
                break;

            case 3:
                /********** Product：サブ画像 **********/
                $stmt = $pdo -> prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main != 1");
                $stmt -> bindValue(":id", $_SESSION['productId'], PDO::PARAM_INT);
                break;

            case 4:
                /********** Cart **********/
                $stmt = $pdo -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price
                    FROM product_images AS pi
                    JOIN products AS p ON pi.product_id = p.id
                    WHERE pi.is_main = 1
                    AND p.category_id = 2
                    AND  p.hidden_at IS NULL
                    ORDER BY p.id
                ");
                break;
        }
        $stmt -> execute();

        return ($fetchType === 0) ? $stmt -> fetch(PDO::FETCH_ASSOC) : $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
?>