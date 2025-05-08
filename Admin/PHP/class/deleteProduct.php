<?php
    class DeleteProduct {
        private $pdo;

        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        /*======================================================*/
        /* 用途：商品の「論理」削除                         	  */
        /* 引数：$deletingItemIds：削除（非表示）する商品配列      */
        /* 戻り値：SQL実行結果									  */
        /* 備考：なし											 */
        /*======================================================*/
        public function deleteProduct(array $deletingItemIds) {
            $placeholders = implode(',', array_fill(0, count($deletingItemIds), '?'));

            $stmt = $this -> pdo -> prepare(
                "UPDATE products
                 SET hidden_at = NOW()
                 WHERE id IN ($placeholders)
                ");
    
            foreach($deletingItemIds as $index => $id) {
                $stmt -> bindValue($index + 1, (int)$id, PDO::PARAM_INT);
            }
            $stmt -> execute();
        }
    }
?>